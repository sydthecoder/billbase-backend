<?php

namespace App\Modules\Quotes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'       => 'sometimes|integer|exists:customers,id',
            'title'             => 'nullable|string|max:255',
            'issue_date'        => 'sometimes|date',
            'expires_at'        => 'sometimes|date',
            'discount_amount'   => 'nullable|numeric|min:0',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'notes'             => 'nullable|string',
            'footer'            => 'nullable|string',
            'items'             => 'sometimes|array|min:1',
            'items.*.product_id'      => 'nullable|integer|exists:products,id',
            'items.*.description'     => 'required_with:items|string',
            'items.*.quantity'        => 'required_with:items|numeric|min:0.01',
            'items.*.unit'            => 'nullable|string|max:50',
            'items.*.unit_price'      => 'required_with:items|numeric|min:0',
            'items.*.is_taxable'      => 'sometimes|boolean',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.sort_order'      => 'sometimes|integer',
        ];
    }
}