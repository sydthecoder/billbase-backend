<?php

namespace App\Modules\Settings\Services;

use App\Models\OrganizationBankAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BankAccountService
{
    public function get(User $user): JsonResponse
    {
        $account = OrganizationBankAccount::where('organization_id', $user->organization_id)->first();

        if (! $account) {
            return response()->json([
                'status' => 'success',
                'data'   => null,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $this->format($account),
        ]);
    }

    public function save(User $user, array $data): JsonResponse
    {
        $account = OrganizationBankAccount::updateOrCreate(
            ['organization_id' => $user->organization_id],
            $data
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Bank account saved.',
            'data'    => $this->format($account->fresh()),
        ]);
    }

    private function format(OrganizationBankAccount $account): array
    {
        return [
            'bank_name'      => $account->bank_name,
            'bank_label'     => config('lookup.south_african_banks')[$account->bank_name],
            'account_holder' => $account->account_holder,
            'account_number' => $account->account_number,
            'branch_code'    => $account->branch_code,
            'account_type'   => $account->account_type,
            'account_type_label' => config('lookup.account_types')[$account->account_type],
            'is_active'      => $account->is_active,
        ];
    }
}