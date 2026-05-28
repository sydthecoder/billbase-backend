<?php

use App\Modules\Invoices\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/invoices')->middleware('auth:sanctum')->group(function () {
    Route::get('/',           [InvoiceController::class, 'index']);
    Route::post('/',          [InvoiceController::class, 'store']);
    Route::get('/{id}',       [InvoiceController::class, 'show']);
    Route::put('/{id}',       [InvoiceController::class, 'update']);
    Route::delete('/{id}',    [InvoiceController::class, 'destroy']);
    Route::post('/{id}/send', [InvoiceController::class, 'send']);
    Route::get('/{id}/pdf',   [InvoiceController::class, 'pdf']);
});