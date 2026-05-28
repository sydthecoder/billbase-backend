<?php

namespace App\Modules\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordPaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:eft,cash,other',
            'amount'         => 'required|numeric|min:0.01',
            'paid_at'        => 'required|date',
            'notes'          => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.in' => 'Only eft, cash, or other are accepted at this stage.',
        ];
    }
}