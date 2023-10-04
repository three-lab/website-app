<?php

use App\Controllers\Auth\LoginController;
use System\Components\Route;

Route::prefix('/auth', function() {
    Route::middleware('guest', function() {
        Route::get('/login', [LoginController::class, 'show']);
        Route::post('/login', [LoginController::class, 'login']);
    });
});
