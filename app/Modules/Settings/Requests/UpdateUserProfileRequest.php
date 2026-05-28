<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|min:2|max:100',
            'last_name'  => 'sometimes|min:2|max:100',
            'phone'      => 'sometimes|nullable|min:7|max:20',
        ];
    }
}