<?php

use App\Modules\Settings\Controllers\SettingsController;
use App\Modules\Settings\Controllers\MailSettingsController;
use App\Modules\Settings\Controllers\PreferenceController;
use App\Modules\Settings\Controllers\BankAccountController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/settings')->middleware('auth:sanctum')->group(function () {

    // User profile
    Route::get('profile',  [SettingsController::class, 'getProfile']);
    Route::put('profile',  [SettingsController::class, 'updateProfile']);

    // Organization profile — owner and admin only
    Route::get('organization',  [SettingsController::class, 'getOrganization']);
    Route::put('organization',  [SettingsController::class, 'updateOrganization']);

    // Mail settings
    Route::get('mail/outgoing',       [MailSettingsController::class, 'get']);
    Route::post('mail/outgoing',      [MailSettingsController::class, 'save']);
    Route::post('mail/outgoing/test', [MailSettingsController::class, 'test']);

    // Preferences
    Route::get('preferences',  [PreferenceController::class, 'get']);
    Route::put('preferences',  [PreferenceController::class, 'update']);

    // Bank account
    Route::get('bank-account', [BankAccountController::class, 'get']);
    Route::post('bank-account', [BankAccountController::class, 'save']);
});