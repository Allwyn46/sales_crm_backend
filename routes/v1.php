<?php

use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\LogoutController;
use App\Http\Controllers\API\V1\Auth\MeController;
use App\Http\Controllers\API\V1\Leads\LeadController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('auth/login', [LoginController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [LogoutController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me', [MeController::class, 'me'])->name('auth.me');

        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::post('leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    });
});
