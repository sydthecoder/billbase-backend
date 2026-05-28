<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'plan_slug' => 'nullable|string|exists:plans,slug',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'An account with this email already exists.',
            'password.confirmed' => 'Passwords do not match.',
            'plan_slug.exists'   => 'Selected plan does not exist.',
        ];
    }
}