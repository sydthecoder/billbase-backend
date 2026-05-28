<?php

use App\Modules\Quotes\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/quotes')->middleware('auth:sanctum')->group(function () {
    Route::get('/',              [QuoteController::class, 'index']);
    Route::post('/',             [QuoteController::class, 'store']);
    Route::get('/{id}',          [QuoteController::class, 'show']);
    Route::put('/{id}',          [QuoteController::class, 'update']);
    Route::delete('/{id}',       [QuoteController::class, 'destroy']);
    Route::patch('/{id}/status', [QuoteController::class, 'updateStatus']);
    Route::get('/{id}/pdf', [QuoteController::class, 'pdf']);
    Route::get('/{id}/pdf/download', [QuoteController::class, 'pdfDownload']);
});