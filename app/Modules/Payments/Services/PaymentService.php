<?php

namespace App\Modules\Payments\Services;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Modules\Invoices\Services\InvoicePdfService;
use App\Modules\Payments\Resources\PaymentResource;
use Illuminate\Http\JsonResponse;

class PaymentService
{
    public function __construct(
        protected InvoicePdfService $invoicePdfService,
    ) {}

    public function index(User $user, int $invoiceId): JsonResponse
    {
        $invoice  = Invoice::where('organization_id', $user->organization_id)->findOrFail($invoiceId);
        $payments = Payment::where('invoice_id', $invoice->id)
            ->with(['createdBy', 'invoice'])
            ->orderBy('paid_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => PaymentResource::collection($payments),
        ]);
    }

    public function record(User $user, int $invoiceId, array $data): JsonResponse
    {
        $invoice = Invoice::where('organization_id', $user->organization_id)
            ->with('organization')
            ->findOrFail($invoiceId);

        // Cannot record payment on cancelled invoice
        if ($invoice->status === 'cancelled') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Cannot record payment on a cancelled invoice.',
            ], 422);
        }

        // Cannot record payment on already paid invoice
        if ($invoice->status === 'paid') {
            return response()->json([
                'status'  => 'error',
                'message' => 'This invoice is already fully paid.',
            ], 422);
        }

        // Validate amount does not exceed amount due
        $amount = (float) $data['amount'];

        if ($amount > $invoice->amount_due) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Payment amount exceeds the amount due (R ' . number_format($invoice->amount_due, 2) . ').',
            ], 422);
        }

        // Create payment record
        $payment = Payment::create([
            'organization_id'  => $user->organization_id,
            'invoice_id'       => $invoice->id,
            'payment_method'   => $data['payment_method'],
            'gateway'          => null, // EFT phase — no gateway
            'gateway_reference'=> null,
            'gateway_status'   => null,
            'amount'           => $amount,
            'currency'         => 'ZAR',
            'status'           => 'completed',
            'notes'            => $data['notes'] ?? null,
            'paid_at'          => $data['paid_at'],
            'created_by'       => $user->id,
        ]);

        // Update invoice amount_paid
        $newAmountPaid = (float) $invoice->amount_paid + $amount;

        $invoiceUpdate = ['amount_paid' => $newAmountPaid];

        if ($newAmountPaid >= (float) $invoice->total) {
            // Fully paid — lock it and generate stored PDF
            $invoiceUpdate['status']    = 'paid';
            $invoiceUpdate['paid_at']   = now();
            $invoiceUpdate['is_locked'] = true;

            $invoice->update($invoiceUpdate);

            // Generate and store PDF — paid invoices are frozen legal documents
            $pdfPath = $this->invoicePdfService->generateAndStore($invoice->fresh());
            $invoice->update(['pdf_path' => $pdfPath]);

        } else {
            // Partial payment
            $invoiceUpdate['status'] = 'partial';
            $invoice->update($invoiceUpdate);
        }

        $payment->load(['createdBy', 'invoice']);

        return response()->json([
            'status'  => 'success',
            'message' => 'Payment recorded.',
            'data'    => new PaymentResource($payment),
        ], 201);
    }
}