<?php

use App\Controllers\Api\AuthController;
use System\Components\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api', function() {
    Route::get('/user', [AuthController::class, 'user']);
});
