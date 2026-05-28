<?php

namespace App\Modules\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestMailSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient' => 'required|email',
        ];
    }
}