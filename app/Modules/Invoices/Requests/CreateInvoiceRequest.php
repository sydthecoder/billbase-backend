<?php

namespace App\Modules\Invoices\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'             => 'required|integer|exists:customers,id',
            'quote_id'                => 'nullable|integer|exists:quotes,id',
            'issue_date'              => 'required|date',
            'due_date'                => 'required|date|after_or_equal:issue_date',
            'notes'                   => 'nullable|string',
            'footer'                  => 'nullable|string',
            'discount_amount'         => 'nullable|numeric|min:0',
            'discount_percent'        => 'nullable|numeric|min:0|max:100',
            'items'                   => 'required|array|min:1',
            'items.*.product_id'      => 'nullable|integer|exists:products,id',
            'items.*.description'     => 'required|string|max:255',
            'items.*.quantity'        => 'required|numeric|min:0.01',
            'items.*.unit'            => 'nullable|string|max:50',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'items.*.is_taxable'      => 'required|boolean',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.sort_order'      => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'               => 'An invoice must have at least one line item.',
            'items.*.description.required' => 'Each line item must have a description.',
            'items.*.quantity.required'    => 'Each line item must have a quantity.',
            'items.*.unit_price.required'  => 'Each line item must have a unit price.',
            'items.*.is_taxable.required'  => 'Each line item must specify if it is taxable.',
            'due_date.after_or_equal'      => 'Due date must be on or after the issue date.',
        ];
    }
}