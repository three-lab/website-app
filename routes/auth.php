<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
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

    Route::get('/logout', [LoginController::class, 'logout']);
});
