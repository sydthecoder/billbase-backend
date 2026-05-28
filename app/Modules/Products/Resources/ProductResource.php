<?php

namespace App\Modules\Products\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'unit'        => $this->unit,
            'unit_label'  => $this->unit
                                ? (config('lookup.product_units')[$this->unit] ?? $this->unit)
                                : null,
            'is_taxable'  => $this->is_taxable,
            'sku'         => $this->sku,
            'status'      => $this->status,
            'category'    => $this->product_category_id ? [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
            ] : null,
            'created_at'  => $this->created_at,
        ];
    }
}