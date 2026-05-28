<?php

use App\Modules\Products\Controllers\ProductCategoryController;
use App\Modules\Products\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/products')->middleware('auth:sanctum')->group(function () {

    // Categories
    Route::get('categories',         [ProductCategoryController::class, 'index']);
    Route::post('categories',        [ProductCategoryController::class, 'store']);
    Route::put('categories/{id}',    [ProductCategoryController::class, 'update']);
    Route::delete('categories/{id}', [ProductCategoryController::class, 'destroy']);

    // Products
    Route::get('/',        [ProductController::class, 'index']);
    Route::post('/',       [ProductController::class, 'store']);
    Route::get('/{id}',    [ProductController::class, 'show']);
    Route::put('/{id}',    [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);

});