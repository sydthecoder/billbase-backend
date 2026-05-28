<?php

namespace App\Modules\Invoices\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'invoice_number'   => $this->invoice_number,
            'status'           => $this->status,
            'is_locked'        => $this->is_locked,
            'issue_date'       => $this->issue_date,
            'due_date'         => $this->due_date,
            'is_overdue'       => $this->is_overdue,
            'subtotal'         => $this->subtotal,
            'discount_amount'  => $this->discount_amount,
            'discount_percent' => $this->discount_percent,
            'tax_total'        => $this->tax_total,
            'total'            => $this->total,
            'amount_paid'      => $this->amount_paid,
            'amount_due'       => $this->amount_due,
            'notes'            => $this->notes,
            'footer'           => $this->footer,
            'sent_at'          => $this->sent_at,
            'viewed_at'        => $this->viewed_at,
            'paid_at'          => $this->paid_at,
            'billing'          => [
                'name'           => $this->billing_name,
                'company'        => $this->billing_company,
                'vat_number'     => $this->billing_vat_number,
                'street_address' => $this->billing_street_address,
                'suburb'         => $this->billing_suburb,
                'city'           => $this->billing_city,
                'province'       => $this->billing_province,
                'postal_code'    => $this->billing_postal_code,
            ],
            'customer' => [
                'id'      => $this->customer->id,
                'name'    => $this->customer->first_name . ' ' . $this->customer->last_name,
                'email'   => $this->customer->email,
                'company' => $this->customer->company_name,
            ],
            'items' => $this->items->map(fn($item) => [
                'id'              => $item->id,
                'product_id'      => $item->product_id,
                'description'     => $item->description,
                'quantity'        => $item->quantity,
                'unit'            => $item->unit,
                'unit_price'      => $item->unit_price,
                'is_taxable'      => $item->is_taxable,
                'tax_rate'        => $item->tax_rate,
                'discount_amount' => $item->discount_amount,
                'line_total'      => $item->line_total,
                'sort_order'      => $item->sort_order,
            ]),
            'payments' => $this->payments->map(fn($p) => [
                'id'             => $p->id,
                'payment_method' => $p->payment_method,
                'amount'         => $p->amount,
                'status'         => $p->status,
                'paid_at'        => $p->paid_at,
                'notes'          => $p->notes,
            ]),
            'created_by' => [
                'id'   => $this->createdBy->id,
                'name' => $this->createdBy->first_name . ' ' . $this->createdBy->last_name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}