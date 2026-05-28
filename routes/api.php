<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

require base_path('app/Modules/Plans/Routes.php');
require base_path('app/Modules/Lookup/Routes.php');
require base_path('app/Modules/Auth/Routes.php');
require base_path('app/Modules/Customers/Routes.php');
require base_path('app/Modules/Products/Routes.php');
require base_path('app/Modules/Quotes/Routes.php');
require base_path('app/Modules/Invoices/Routes.php');
require base_path('app/Modules/Payments/Routes.php');
require base_path('app/Modules/Settings/Routes.php');