<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    // REGISTER
    Route::get('register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    
    // LOGIN
    Route::get('login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',    [AuthenticatedSessionController::class, 'store']);

    // RESET PASSWORD REQUEST
    Route::get('forgot-password',   [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('forgot-password',  [PasswordResetController::class, 'store'])->name('password.email');

    // RESET PASSWORD
    Route::get('reset-password/{token}',    [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password',           [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::controller(EmailVerificationController::class)->group(function () {
        Route::get('verifikasi-email',              'index')->name('email-verification');
        Route::get('kirim-ulang-verifikasi-email',  'resend')->name('resend-email-verification');
        Route::post('verifikasi-email',             'verify')->name('verify-email');
    });

    Route::get('confirm-password',  [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password',          [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
