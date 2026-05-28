<?php

namespace App\Modules\Quotes\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'product_id'      => $this->product_id,
            'description'     => $this->description,
            'quantity'        => $this->quantity,
            'unit'            => $this->unit,
            'unit_price'      => $this->unit_price,
            'is_taxable'      => $this->is_taxable,
            'tax_rate'        => $this->tax_rate,
            'discount_amount' => $this->discount_amount,
            'line_total'      => $this->line_total,
            'sort_order'      => $this->sort_order,
        ];
    }
}