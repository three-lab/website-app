<?php

use App\Controllers\Api\AttendanceController;
use App\Controllers\Api\AuthController;
use System\Components\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-pass', [AuthController::class, 'forgotPass']);
Route::post('/verify-code', [AuthController::class, 'verifyCode']);
Route::post('/reset-pass', [AuthController::class, 'resetPass']);

Route::middleware('auth:api', function() {
    Route::get('/user', [AuthController::class, 'user']);

    Route::prefix('/attendances', function() {
        Route::post('/attempt', [AttendanceController::class, 'attempt']);
        Route::get('/status', [AttendanceController::class, 'status']);
    });
});
