<?php

namespace App\Modules\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'full_name'  => trim($this->first_name . ' ' . $this->last_name) ?: null,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'role'       => $this->role,
            'avatar_url' => $this->avatar_url,
            'last_login_at' => $this->last_login_at,
            'organization'  => [
                'id'       => $this->organization->id,
                'org_code' => $this->organization->org_code,
                'name'     => $this->organization->name,
                'email'    => $this->organization->email,
                'phone'    => $this->organization->phone,
                'logo_url' => $this->organization->logo_url,
                'address'  => [
                    'street_address' => $this->organization->street_address,
                    'suburb'         => $this->organization->suburb,
                    'city'           => $this->organization->city,
                    'province'       => $this->organization->province,
                    'postal_code'    => $this->organization->postal_code,
                    'country'        => $this->organization->country,
                ],
                'currency'   => $this->organization->currency,
                'reg_number' => $this->organization->reg_number,
                'tax_number' => $this->organization->tax_number,
                'status'     => $this->organization->status,
                'subscription' => [
                    'status'        => $this->organization->subscription?->status,
                    'trial_ends_at' => $this->organization->subscription?->trial_ends_at,
                ],
            ],
        ];
    }
}