<?php

namespace App\Modules\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name'       => 'nullable|string|max:150',
            'company_reg_number' => 'nullable|string|max:100',
            'vat_number'         => 'nullable|string|max:100',
            'first_name'         => 'sometimes|string|max:100',
            'last_name'          => 'sometimes|string|max:100',
            'email'              => 'sometimes|email|max:150',
            'phone'              => 'nullable|string|max:50',
            'street_address'     => 'nullable|string|max:255',
            'suburb'             => 'nullable|string|max:100',
            'city'               => 'nullable|string|max:100',
            'province'           => 'nullable|in:' . implode(',', array_keys(config('lookup.provinces'))),
            'postal_code'        => 'nullable|string|max:10',
            'notes'              => 'nullable|string',
            'status'             => 'sometimes|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required_if' => 'Company name is required for business customers.',
            'province.in'              => 'Invalid province selected.',
        ];
    }
}