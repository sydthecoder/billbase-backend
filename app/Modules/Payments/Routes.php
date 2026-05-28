<?php

use App\Modules\Payments\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('invoices/{invoiceId}/payments', [PaymentController::class, 'store']);
    Route::get('invoices/{invoiceId}/payments',  [PaymentController::class, 'index']);
});

// GATEWAY WEBHOOKS — PHASE 2 (stubbed — do not build now)
// Route::post('webhooks/paystack', [WebhookController::class, 'paystack']);
// Route::post('webhooks/payfast',  [WebhookController::class, 'payfast']);
// Route::post('webhooks/ozow',     [WebhookController::class, 'ozow']);