<?php

use App\Modules\Lookup\Controllers\LookupController;
use Illuminate\Support\Facades\Route;

// Public — no auth needed
Route::prefix('v1/lookup')->group(function () {
    Route::get('banks',         [LookupController::class, 'banks']);
    Route::get('account-types', [LookupController::class, 'accountTypes']);
    Route::get('provinces',     [LookupController::class, 'provinces']);
    Route::get('payment-terms', [LookupController::class, 'paymentTerms']);
    Route::get('product-units', [LookupController::class, 'productUnits']);
});