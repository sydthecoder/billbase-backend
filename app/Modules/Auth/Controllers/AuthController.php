<?php

namespace App\Modules\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationSubscription;
use App\Models\User;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Resources\UserResource;
use App\Services\CodeGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // 1. Create organization
            $organization = Organization::create([
                'org_code' => CodeGeneratorService::organization(),
                'country'  => 'ZA',
                'currency' => 'ZAR',
                'status'   => 'active',
            ]);

            // Free trial: default to plan 2 for now; we will scale plan selection later.
            $planId = $request->plan_id ?? 2;

            OrganizationSubscription::create([
                'organization_id' => $organization->id,
                'plan_id'         => $planId,
                'status'          => 'trialing',
                'trial_ends_at'   => now()->addDays(14),
            ]);

            $user = User::create([
                'organization_id' => $organization->id,
                'first_name'      => 'System',
                'last_name'       => 'Admin',
                'email'           => $request->email,
                'password'        => Hash::make($request->password),
                'role'            => 'owner',
            ]);

            DB::commit();

            // 4. Generate token
            $token = $user->createToken('auth_token')->plainTextToken;

            // 5. Load relationships for resource
            $user->load('organization.subscription');

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                    'user'         => new UserResource($user),
                ],
            ], 201)->cookie($this->authCookie($token));

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Registration failed. Please try again.',
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // Revoke previous tokens — one active session at a time
        $user->tokens()->delete();

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->load('organization.subscription');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => new UserResource($user),
            ],
        ], 200)->cookie($this->authCookie($token));
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()?->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out.',
        ], 200)->cookie(Cookie::forget('auth_token'));
    }

    public function me(): JsonResponse
    {
        $user = auth()->user()->load('organization.subscription');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'user' => new UserResource($user),
            ],
        ], 200);
    }

    private function authCookie(string $token): SymfonyCookie
    {
        $secure = (bool) config('session.secure', app()->environment('production'));
        $sameSite = config('session.same_site', 'lax') ?: 'lax';

        return cookie(
            'auth_token',
            $token,
            (int) config('session.lifetime', 120),
            '/',
            config('session.domain'),
            $secure,
            true,
            false,
            $sameSite
        );
    }
}