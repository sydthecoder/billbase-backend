<?php

namespace App\Modules\Quotes\Services;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use App\Modules\Quotes\Resources\QuoteResource;
use App\Services\CodeGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public function index(User $user): JsonResponse
    {
        $quotes = Quote::where('organization_id', $user->organization_id)
            ->with(['customer', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => QuoteResource::collection($quotes),
        ]);
    }

    public function store(User $user, array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $totals = $this->calculateTotals($data['items'], $data);

            $quote = Quote::create([
                'organization_id'  => $user->organization_id,
                'customer_id'      => $data['customer_id'],
                'created_by'       => $user->id,
                'quote_number'     => CodeGeneratorService::quote($user->organization_id),
                'title'            => $data['title'] ?? null,
                'status'           => 'draft',
                'issue_date'       => $data['issue_date'],
                'expires_at'       => $data['expires_at'],
                'discount_amount'  => $data['discount_amount'] ?? 0,
                'discount_percent' => $data['discount_percent'] ?? 0,
                'subtotal'         => $totals['subtotal'],
                'tax_total'        => $totals['tax_total'],
                'total'            => $totals['total'],
                'notes'            => $data['notes'] ?? null,
                'footer'           => $data['footer'] ?? config('settings.organization_preferences.invoice_footer'),
            ]);

            $this->syncItems($quote, $data['items']);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Quote created.',
                'data'    => new QuoteResource($quote->load(['customer', 'createdBy', 'items'])),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create quote: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user, int $id): JsonResponse
    {
        $quote = Quote::where('organization_id', $user->organization_id)
            ->with(['customer', 'createdBy', 'items'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => new QuoteResource($quote),
        ]);
    }

    public function update(User $user, int $id, array $data): JsonResponse
    {
        $quote = Quote::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        // Lock check
        if ($quote->isLocked()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This quote is locked and cannot be edited.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $updateData = collect($data)->except('items')->toArray();

            // Recalculate if items are being updated
            if (isset($data['items'])) {
                $totals = $this->calculateTotals($data['items'], array_merge(
                    $quote->toArray(),
                    $data
                ));

                $updateData['subtotal']  = $totals['subtotal'];
                $updateData['tax_total'] = $totals['tax_total'];
                $updateData['total']     = $totals['total'];

                $this->syncItems($quote, $data['items']);
            }

            $quote->update($updateData);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Quote updated.',
                'data'    => new QuoteResource($quote->fresh()->load(['customer', 'createdBy', 'items'])),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update quote: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(User $user, int $id): JsonResponse
    {
        $quote = Quote::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        if ($quote->isLocked()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This quote is locked and cannot be deleted.',
            ], 422);
        }

        $quote->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Quote deleted.',
        ]);
    }

    public function updateStatus(User $user, int $id, string $status): JsonResponse
    {
        $quote = Quote::where('organization_id', $user->organization_id)
            ->findOrFail($id);

        if ($quote->isLocked()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This quote is locked and its status cannot be changed.',
            ], 422);
        }

        $timestamps = [];

        if ($status === 'sent') {
            $timestamps['sent_at'] = now();
        }

        $quote->update(array_merge(['status' => $status], $timestamps));

        return response()->json([
            'status'  => 'success',
            'message' => 'Quote status updated.',
            'data'    => new QuoteResource($quote->fresh()->load(['customer', 'createdBy', 'items'])),
        ]);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function syncItems(Quote $quote, array $items): void
    {
        // Delete existing items and replace — clean replace strategy
        $quote->items()->delete();

        foreach ($items as $index => $item) {
            $lineTotal = $this->calculateLineTotal($item);

            QuoteItem::create([
                'quote_id'        => $quote->id,
                'product_id'      => $item['product_id'] ?? null,
                'description'     => $item['description'],
                'quantity'        => $item['quantity'],
                'unit'            => $item['unit'] ?? null,
                'unit_price'      => $item['unit_price'],
                'is_taxable'      => $item['is_taxable'] ?? true,
                // Snapshot tax rate at creation time from config
                'tax_rate'        => ($item['is_taxable'] ?? true)
                                        ? config('settings.tax.rate')
                                        : 0,
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
            $lineTotal  = $this->calculateLineTotal($item);
            $subtotal  += $lineTotal;
            $isTaxable  = $item['is_taxable'] ?? true;

            if ($isTaxable) {
                $taxTotal += round($lineTotal * ($taxRate / 100), 2);
            }
        }

        // Apply quote-level discount
        $discountAmount  = (float) ($data['discount_amount'] ?? 0);
        $discountPercent = (float) ($data['discount_percent'] ?? 0);

        if ($discountPercent > 0) {
            $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        }

        $total = round(($subtotal + $taxTotal) - $discountAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'total'    => $total,
        ];
    }
}