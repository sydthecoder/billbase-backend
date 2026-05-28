<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationBankAccount;
use App\Services\OrganizationSettingsResolver;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BankAccountService
{
    public function get(User $user): JsonResponse
    {
        $account = OrganizationSettingsResolver::for($user->organization_id)->bankAccount();

        return response()->json([
            'status' => 'success',
            'data'   => $account, // null if none saved yet
        ]);
    }

    public function save(User $user, array $data): JsonResponse
    {
        OrganizationBankAccount::updateOrCreate(
            ['organization_id' => $user->organization_id],
            $data
        );

        $account = OrganizationSettingsResolver::for($user->organization_id)->bankAccount();

        return response()->json([
            'status'  => 'success',
            'message' => 'Bank account saved.',
            'data'    => $account,
        ]);
    }
}