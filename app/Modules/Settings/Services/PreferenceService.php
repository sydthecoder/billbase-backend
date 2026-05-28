<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationPreference;
use App\Services\OrganizationSettingsResolver;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PreferenceService
{
    public function get(User $user): JsonResponse
    {
        $resolver = OrganizationSettingsResolver::for($user->organization_id);

        return response()->json([
            'status' => 'success',
            'data'   => $resolver->allPreferences(),
        ]);
    }

    public function update(User $user, array $data): JsonResponse
    {
        OrganizationPreference::updateOrCreate(
            ['organization_id' => $user->organization_id],
            $data
        );

        $resolver = OrganizationSettingsResolver::for($user->organization_id);

        return response()->json([
            'status' => 'success',
            'data'   => $resolver->allPreferences(),
        ]);
    }
}