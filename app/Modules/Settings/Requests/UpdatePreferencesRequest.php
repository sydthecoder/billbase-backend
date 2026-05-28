<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_prefix'          => 'sometimes|string|max:20',
            'invoice_starting_number' => 'sometimes|integer|min:1',
            'default_payment_terms'   => 'sometimes|integer|min:1',
            'invoice_footer'          => 'sometimes|nullable|string',
            'invoice_notes'           => 'sometimes|nullable|string',
            'quote_prefix'            => 'sometimes|string|max:20',
            'quote_starting_number'   => 'sometimes|integer|min:1',
            'customer_code_prefix'    => 'sometimes|string|max:20',
            'brand_color'             => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'brand_color.regex' => 'Brand color must be a valid hex code e.g. #0F766E',
        ];
    }
}