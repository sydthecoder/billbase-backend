<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Requests\UpdatePreferencesRequest;
use App\Modules\Settings\Services\PreferenceService;
use Illuminate\Http\JsonResponse;

class PreferenceController extends Controller
{
    public function __construct(
        protected PreferenceService $preferenceService,
    ) {}

    public function get(): JsonResponse
    {
        return $this->preferenceService->get(auth()->user());
    }

    public function update(UpdatePreferencesRequest $request): JsonResponse
    {
        return $this->preferenceService->update(auth()->user(), $request->validated());
    }
}