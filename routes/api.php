<?php

use App\Controllers\Api\AuthController;
use System\Components\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-pass', [AuthController::class, 'forgotPass']);
Route::post('/verify-code', [AuthController::class, 'verifyCode']);

Route::middleware('auth:api', function() {
    Route::get('/user', [AuthController::class, 'user']);
});
