<?php

namespace App\Modules\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'             => 'sometimes|integer|exists:customers,id',
            'issue_date'              => 'sometimes|date',
            'due_date'                => 'sometimes|date',
            'notes'                   => 'nullable|string',
            'footer'                  => 'nullable|string',
            'discount_amount'         => 'nullable|numeric|min:0',
            'discount_percent'        => 'nullable|numeric|min:0|max:100',
            'items'                   => 'sometimes|array|min:1',
            'items.*.product_id'      => 'nullable|integer|exists:products,id',
            'items.*.description'     => 'required_with:items|string|max:255',
            'items.*.quantity'        => 'required_with:items|numeric|min:0.01',
            'items.*.unit'            => 'nullable|string|max:50',
            'items.*.unit_price'      => 'required_with:items|numeric|min:0',
            'items.*.is_taxable'      => 'required_with:items|boolean',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.sort_order'      => 'nullable|integer|min:0',
        ];
    }
}