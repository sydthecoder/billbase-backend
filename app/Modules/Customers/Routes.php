<?php

use App\Modules\Customers\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/customers')->middleware('auth:sanctum')->group(function () {
    Route::get('/',        [CustomerController::class, 'index']);
    Route::post('/',       [CustomerController::class, 'store']);
    Route::get('/{id}',    [CustomerController::class, 'show']);
    Route::put('/{id}',    [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
});