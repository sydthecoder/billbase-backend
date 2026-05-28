<?php

namespace App\Modules\Settings\Requests;

use App\Models\OrganizationMailSetting;
use Illuminate\Foundation\Http\FormRequest;

class SaveMailSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driver = $this->input('driver', 'smtp');

        $user = $this->user();
        $existing = null;
        if ($user) {
            $existing = OrganizationMailSetting::where('organization_id', $user->organization_id)->first();
        }

        $rules = [
            'driver'     => 'required|in:smtp,brevo',
            'from_name'  => 'required|max:150',
            'from_email' => 'required|email',
        ];

        // Driver-specific config rules
        if ($driver === 'smtp') {
            $passwordRule = ($existing && $existing->driver === 'smtp') ? 'nullable' : 'required';
            $rules = array_merge($rules, [
                'config.host'       => 'required',
                'config.port'       => 'required|integer',
                'config.encryption' => 'required|in:tls,ssl,starttls',
                'config.username'   => 'required',
                'config.password'   => $passwordRule,
            ]);
        }

        if ($driver === 'brevo') {
            $apiKeyRule = ($existing && $existing->driver === 'brevo') ? 'nullable' : 'required';
            $rules = array_merge($rules, [
                'config.api_key' => $apiKeyRule,
            ]);
        }

        return $rules;
    }
}