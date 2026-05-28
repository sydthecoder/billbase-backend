<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Settings\Requests\SaveBankAccountRequest;
use App\Modules\Settings\Services\BankAccountService;
use Illuminate\Http\JsonResponse;

class BankAccountController extends Controller
{
    public function __construct(
        protected BankAccountService $bankAccountService,
    ) {}

    public function get(): JsonResponse
    {
        return $this->bankAccountService->get(auth()->user());
    }

    public function save(SaveBankAccountRequest $request): JsonResponse
    {
        return $this->bankAccountService->save(auth()->user(), $request->validated());
    }
}