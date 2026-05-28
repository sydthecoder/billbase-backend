<?php

use App\Modules\Plans\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

// Public — no auth needed, marketing page fetches this
Route::prefix('v1')->group(function () {
    Route::get('plans', [PlanController::class, 'index']);
});