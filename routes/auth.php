<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('forgot-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    Route::get('reset-password/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [App\Http\Controllers\Auth\PasswordResetController::class, 'reset'])
        ->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
