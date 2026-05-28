<?php

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Requests\RecordPaymentRequest;
use App\Modules\Payments\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {}

    public function index(int $invoiceId): JsonResponse
    {
        return $this->paymentService->index(auth()->user(), $invoiceId);
    }

    public function store(RecordPaymentRequest $request, int $invoiceId): JsonResponse
    {
        return $this->paymentService->record(auth()->user(), $invoiceId, $request->validated());
    }
}