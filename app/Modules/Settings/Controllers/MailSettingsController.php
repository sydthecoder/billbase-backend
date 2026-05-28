<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Requests\SaveMailSettingsRequest;
use App\Modules\Settings\Requests\TestMailSettingsRequest;
use App\Modules\Settings\Services\MailSettingsService;
use Illuminate\Http\JsonResponse;

class MailSettingsController extends Controller
{
    public function __construct(
        protected MailSettingsService $mailSettingsService,
    ) {}

    public function get(): JsonResponse
    {
        return $this->mailSettingsService->get(auth()->user());
    }

    public function save(SaveMailSettingsRequest $request): JsonResponse
    {
        return $this->mailSettingsService->save(auth()->user(), $request->validated());
    }

    public function test(TestMailSettingsRequest $request): JsonResponse
    {
        return $this->mailSettingsService->test(auth()->user(), $request->validated()['recipient']);
    }
}