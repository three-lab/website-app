<?php

use App\Controllers\Auth\ForgotPasswordController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\ResetPasswordController;
use App\Controllers\Auth\VerificationController;
use App\Controllers\DashboardController;
use System\Components\Route;

Route::prefix('/auth', function() {
    Route::middleware('guest', function() {
        Route::get('/login', [LoginController::class, 'show']);
        Route::post('/login', [LoginController::class, 'login']);

        Route::get('/forgot-password', [ForgotPasswordController::class, 'show']);
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendCode']);

        Route::get('/verify-code', [VerificationController::class, 'show']);
        Route::post('/verify-code', [VerificationController::class, 'verifyCode']);

        Route::get('/reset-password', [ResetPasswordController::class, 'show']);
        Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
    });
});

Route::middleware('auth:web', function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
