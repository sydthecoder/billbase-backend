<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
        ];
    }
}