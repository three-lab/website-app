<?php

use App\Bootstrap\Route;
use App\Controllers\HomeController;

Route::prefix('/article', function() {
    Route::post('/read', [HomeController::class, 'article']);
});

Route::get('/home', [HomeController::class, 'index']);

Route::middleware('auth', function() {
    Route::get('/admin', [HomeController::class, 'admin']);
});
