<?php

namespace App\Modules\Settings\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class OrganizationProfileService
{
    public function get(User $user): JsonResponse
    {
        $org = $user->organization;

        return response()->json([
            'status' => 'success',
            'data'   => $this->formatOrg($org),
        ]);
    }

    public function update(User $user, array $data): JsonResponse
    {
        $org = $user->organization;
        $org->update($data);

        return response()->json([
            'status' => 'success',
            'data'   => $this->formatOrg($org->fresh()),
        ]);
    }

    private function formatOrg(Organization $org): array
    {
        return [
            'id'       => $org->id,
            'org_code' => $org->org_code,
            'name'     => $org->name,
            'email'    => $org->email,
            'phone'    => $org->phone,
            'address'  => [
                'street_address' => $org->street_address,
                'suburb'         => $org->suburb,
                'city'           => $org->city,
                'province'       => $org->province,
                'postal_code'    => $org->postal_code,
                'country'        => $org->country,
            ],
            'reg_number' => $org->reg_number,
            'tax_number' => $org->tax_number,
            'currency'   => $org->currency,
            'status'     => $org->status,
        ];
    }
}