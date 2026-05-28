<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_name'      => 'required|in:' . implode(',', array_keys(config('lookup.south_african_banks'))),
            'account_holder' => 'required|string|max:150',
            'account_number' => 'required|string|max:50',
            'branch_code'    => 'required|string|max:20',
            'account_type'   => 'required|in:' . implode(',', array_keys(config('lookup.account_types'))),
        ];
    }

    public function messages(): array
    {
        return [
            'bank_name.in'    => 'Invalid bank selected.',
            'account_type.in' => 'Invalid account type selected.',
        ];
    }
}