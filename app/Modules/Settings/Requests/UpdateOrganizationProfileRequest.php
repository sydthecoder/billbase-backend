<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $orgId = auth()->user()->organization_id;

        return [
            'name'           => 'sometimes|min:2|max:150',
            'email'          => [
                'sometimes',
                'email',
                Rule::unique('organizations', 'email')->ignore($orgId),
            ],
            'phone'          => 'sometimes|nullable|min:7|max:20',
            'reg_number'     => 'sometimes|nullable|max:100',
            'tax_number'     => 'sometimes|nullable|max:100',
            'street_address' => 'sometimes|nullable|max:255',
            'suburb'         => 'sometimes|nullable|max:100',
            'city'           => 'sometimes|nullable|max:100',
            'province'       => 'sometimes|nullable|max:100',
            'postal_code'    => 'sometimes|nullable|max:10',
            'country'        => 'sometimes|nullable|max:10',
            'currency'       => 'sometimes|nullable|max:10',
        ];
    }
}