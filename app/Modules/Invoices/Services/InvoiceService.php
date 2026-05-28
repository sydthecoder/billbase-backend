<?php

namespace App\Modules\Invoices\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OrganizationMailSetting;
use App\Models\Quote;
use App\Models\User;
use App\Modules\Invoices\Resources\InvoiceResource;
use App\Services\CodeGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    public function __construct(
        protected InvoicePdfService $invoicePdfService,
    ) {}

    public function index(User $user): JsonResponse
    {
        $invoices = Invoice::where('organization_id', $user->organization_id)
            ->with(['customer', 'createdBy', 'items', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => InvoiceResource::collection($invoices),
        ]);
    }

    public function store(User $user, array $data): JsonResponse
    {
        // Validate customer belongs to org
        $customer = Customer::where('organization_id', $user->organization_id)
            ->findOrFail($data['customer_id']);

        // If converting from quote — validate quote belongs to org and is accepted
        if (isset($data['quote_id'])) {
            $quote = Quote::where('organization_id', $user->organization_id)
                ->where('status', 'accepted')
                ->findOrFail($data['quote_id']);
        }

        DB::beginTransaction();

        try {
            $totals = $this->calculateTotals($data['items'], $data);

            // SARS billing address snapshot — always from customer at creation time
            $billingSnapshot = [
                'billing_name'           => $customer->first_name . ' ' . $customer->last_name,
                'billing_company'        => $customer->company_name,
                'billing_vat_number'     => $customer->vat_number,
                'billing_street_address' => $customer->street_address,
                'billing_suburb'         => $customer->suburb,
                'billing_city'           => $customer->city,
                'billing_province'       => $customer->province,
                'billing_postal_code'    => $customer->postal_code,
            ];

            $invoice = Invoice::create([
                'organization_id'  => $user->organization_id,
                'customer_id'      => $data['customer_id'],
                'quote_id'         => $data['quote_id'] ?? null,
                'created_by'       => $user->id,
                'invoice_number'   => CodeGeneratorService::invoice($user->organization_id),
                'status'           => 'draft',
                'issue_date'       => $data['issue_date'],
                'due_date'         => $data['due_date'],
                'discount_amount'  => $data['discount_amount'] ?? 0,
                'discount_percent' => $data['discount_percent'] ?? 0,
                'subtotal'         => $totals['subtotal'],
                'tax_total'        => $totals['tax_total'],
                'total'            => $totals['total'],
                'notes'            => $data['notes'] ?? null,
                'footer'           => $data['footer'] ?? config('settings.organization_preferences.invoice_footer'),
                ...$billingSnapshot,
            ]);

            $this->syncItems($invoice, $data['items']);

            // If converted from quote — lock quote
            if (isset($quote)) {
                $quote->update([
                    'status'                  => 'converted',
                    'converted_at'            => now(),
                    'converted_to_invoice_id' => $invoice->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice created.',
                'data'    => new InvoiceResource(
                    $invoice->load(['customer', 'createdBy', 'items', 'payments'])
                ),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user, int $id): JsonResponse
    {
        $invoice = Invoice::where('organization_id', $user->organization_id)
            ->with(['customer', 'createdBy', 'items', 'payments'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => new InvoiceResource($invoice),
        ]);
    }

    public function update(User $user, int $id, array $data): JsonResponse
    {
        $invoice = Invoice::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        // Lock check — NEVER allow editing locked invoices
        if ($invoice->is_locked) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invoice is locked and cannot be edited.',
            ], 422);
        }

        if (in_array($invoice->status, ['paid', 'cancelled'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invoice cannot be edited in its current status.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $updateData = collect($data)->except('items')->toArray();

            if (isset($data['items'])) {
                $totals = $this->calculateTotals($data['items'], array_merge(
                    $invoice->toArray(),
                    $data
                ));

                $updateData['subtotal']  = $totals['subtotal'];
                $updateData['tax_total'] = $totals['tax_total'];
                $updateData['total']     = $totals['total'];

                $this->syncItems($invoice, $data['items']);
            }

            $invoice->update($updateData);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice updated.',
                'data'    => new InvoiceResource(
                    $invoice->fresh()->load(['customer', 'createdBy', 'items', 'payments'])
                ),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(User $user, int $id): JsonResponse
    {
        $invoice = Invoice::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        if ($invoice->is_locked || $invoice->status === 'paid') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Paid or locked invoices cannot be deleted.',
            ], 422);
        }

        $invoice->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Invoice deleted.',
        ]);
    }

    public function send(User $user, int $id): JsonResponse
    {
        $invoice = Invoice::where('organization_id', $user->organization_id)
            ->with(['customer', 'createdBy', 'items', 'payments', 'organization', 'organization.bankAccount'])
            ->findOrFail($id);

        if ($invoice->is_locked && $invoice->status === 'paid') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Paid invoices cannot be resent.',
            ], 422);
        }

        try {
            // Generate PDF for attachment
            $pdf = $this->invoicePdfService->generate($invoice);

            // Get tenant mail settings — fallback to .env if not configured
            $mailSetting = OrganizationMailSetting::where('organization_id', $user->organization_id)
                ->where('is_verified', true)
                ->first();

            if ($mailSetting) {
                $config = $mailSetting->config;
                config([
                    'mail.mailers.smtp.host'      => $config['host'],
                    'mail.mailers.smtp.port'      => $config['port'],
                    'mail.mailers.smtp.encryption' => $config['encryption'],
                    'mail.mailers.smtp.username'   => $config['username'],
                    'mail.mailers.smtp.password'   => $config['password'],
                    'mail.from.address'            => $mailSetting->from_email,
                    'mail.from.name'               => $mailSetting->from_name,
                ]);
            }

            Mail::send([], [], function ($message) use ($invoice, $pdf) {
                $message->to($invoice->customer->email, $invoice->customer->first_name . ' ' . $invoice->customer->last_name)
                    ->subject('Invoice ' . $invoice->invoice_number . ' from ' . ($invoice->organization->name ?? $invoice->organization->org_code))
                    ->text('Please find your invoice attached. Amount due: R ' . number_format((float) $invoice->amount_due, 2) . '. Due date: ' . $invoice->due_date->format('d M Y') . '.')
                    ->attachData($pdf, $invoice->invoice_number . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });

            $invoice->update([
                'sent_at' => now(),
                'status'  => 'sent',
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice sent to ' . $invoice->customer->email,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to send invoice: ' . $e->getMessage(),
            ], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function syncItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();

        foreach ($items as $index => $item) {
            $lineTotal = $this->calculateLineTotal($item);

            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'product_id'      => $item['product_id'] ?? null,
                'description'     => $item['description'],
                'quantity'        => $item['quantity'],
                'unit'            => $item['unit'] ?? null,
                'unit_price'      => $item['unit_price'],
                'is_taxable'      => $item['is_taxable'],
                // Always snapshot from config — never from request
                'tax_rate'        => $item['is_taxable'] ? config('settings.tax.rate') : 0,
                'discount_amount' => $item['discount_amount'] ?? 0,
                'line_total'      => $lineTotal,
                'sort_order'      => $item['sort_order'] ?? $index,
            ]);
        }
    }

    private function calculateLineTotal(array $item): float
    {
        $quantity  = (float) $item['quantity'];
        $unitPrice = (float) $item['unit_price'];
        $discount  = (float) ($item['discount_amount'] ?? 0);

        return round(($quantity * $unitPrice) - $discount, 2);
    }

    private function calculateTotals(array $items, array $data): array
    {
        $subtotal = 0;
        $taxTotal = 0;
        $taxRate  = config('settings.tax.rate');

        foreach ($items as $item) {
            $lineTotal = $this->calculateLineTotal($item);
            $subtotal += $lineTotal;

            if ($item['is_taxable']) {
                $taxTotal += round($lineTotal * ($taxRate / 100), 2);
            }
        }

        $discountAmount  = (float) ($data['discount_amount'] ?? 0);
        $discountPercent = (float) ($data['discount_percent'] ?? 0);

        if ($discountPercent > 0) {
            $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        }

        $total = round(($subtotal + $taxTotal) - $discountAmount, 2);

        return [
            'subtotal'  => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'total'     => $total,
        ];
    }
}