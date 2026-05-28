<?php

namespace App\Modules\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'invoice_id'     => $this->invoice_id,
            'payment_method' => $this->payment_method,
            'gateway'        => $this->gateway,
            'amount'         => $this->amount,
            'currency'       => $this->currency,
            'status'         => $this->status,
            'notes'          => $this->notes,
            'paid_at'        => $this->paid_at,
            'created_by'     => [
                'id'   => $this->createdBy->id,
                'name' => $this->createdBy->first_name . ' ' . $this->createdBy->last_name,
            ],
            'invoice_summary' => [
                'invoice_number' => $this->invoice->invoice_number,
                'total'          => $this->invoice->total,
                'amount_paid'    => $this->invoice->amount_paid,
                'amount_due'     => $this->invoice->amount_due,
                'status'         => $this->invoice->status,
            ],
            'created_at' => $this->created_at,
        ];
    }
}