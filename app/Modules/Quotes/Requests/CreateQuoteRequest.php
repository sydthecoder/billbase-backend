<?php

namespace App\Modules\Quotes\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'       => 'required|integer|exists:customers,id',
            'title'             => 'nullable|string|max:255',
            'issue_date'        => 'required|date',
            'expires_at'        => 'required|date|after:issue_date',
            'discount_amount'   => 'nullable|numeric|min:0',
            'discount_percent'  => 'nullable|numeric|min:0|max:100',
            'notes'             => 'nullable|string',
            'footer'            => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'      => 'nullable|integer|exists:products,id',
            'items.*.description'     => 'required|string',
            'items.*.quantity'        => 'required|numeric|min:0.01',
            'items.*.unit'            => 'nullable|string|max:50',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'items.*.is_taxable'      => 'sometimes|boolean',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.sort_order'      => 'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'             => 'A quote must have at least one line item.',
            'items.*.description.required' => 'Each line item must have a description.',
            'items.*.quantity.required'  => 'Each line item must have a quantity.',
            'items.*.unit_price.required'=> 'Each line item must have a unit price.',
            'expires_at.after'           => 'Expiry date must be after issue date.',
        ];
    }
}