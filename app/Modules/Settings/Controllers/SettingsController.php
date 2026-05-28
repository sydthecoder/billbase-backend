<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Requests\UpdateOrganizationProfileRequest;
use App\Modules\Settings\Requests\UpdateUserProfileRequest;
use App\Modules\Settings\Services\OrganizationProfileService;
use App\Modules\Settings\Services\UserProfileService;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function __construct(
        protected UserProfileService    $userProfileService,
        protected OrganizationProfileService $OrganizationProfileService,
    ) {}

    // User Profile
    public function getProfile(): JsonResponse
    {
        return $this->userProfileService->get(auth()->user());
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        return $this->userProfileService->update(auth()->user(), $request->validated());
    }

    // Organization Profile
    public function getOrganization(): JsonResponse
    {
        return $this->OrganizationProfileService->get(auth()->user());
    }

    public function updateOrganization(UpdateOrganizationProfileRequest $request): JsonResponse
    {
        return $this->OrganizationProfileService->update(auth()->user(), $request->validated());
    }
}