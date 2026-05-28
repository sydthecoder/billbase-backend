<?php

namespace App\Modules\Quotes\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'quote_number'            => $this->quote_number,
            'title'                   => $this->title,
            'status'                  => $this->status,
            'is_locked'               => $this->isLocked(),
            'issue_date'              => $this->issue_date,
            'expires_at'              => $this->expires_at,
            'subtotal'                => $this->subtotal,
            'discount_amount'         => $this->discount_amount,
            'discount_percent'        => $this->discount_percent,
            'tax_total'               => $this->tax_total,
            'total'                   => $this->total,
            'notes'                   => $this->notes,
            'footer'                  => $this->footer,
            'sent_at'                 => $this->sent_at,
            'viewed_at'               => $this->viewed_at,
            'converted_at'            => $this->converted_at,
            'converted_to_invoice_id' => $this->converted_to_invoice_id,
            'customer'                => [
                'id'        => $this->customer?->id,
                'name'      => trim($this->customer?->first_name . ' ' . $this->customer?->last_name),
                'email'     => $this->customer?->email,
                'company'   => $this->customer?->company_name,
            ],
            'created_by' => [
                'id'   => $this->createdBy->id,
                'name' => trim($this->createdBy->first_name . ' ' . $this->createdBy->last_name),
            ],
            'items'      => QuoteItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}