<?php

namespace App\Modules\Settings\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileService
{
    public function get(User $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'full_name'  => trim($user->first_name . ' ' . $user->last_name),
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
            ],
        ]);
    }

    public function update(User $user, array $data): JsonResponse
    {
        $user->update($data);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'full_name'  => trim($user->first_name . ' ' . $user->last_name),
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
            ],
        ]);
    }
}