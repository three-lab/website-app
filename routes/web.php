<?php

use System\Route;
use App\Controllers\HomeController;

Route::middleware('auth', function() {
    Route::post('/article/read', [HomeController::class, 'article']);
});

Route::get('/home', [HomeController::class, 'index']);

Route::middleware('auth', function() {
    Route::get('/admin', [HomeController::class, 'admin']);
});
