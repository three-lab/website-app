<?php

use App\Bootstrap\Route;
use App\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index']);
Route::post('/article', [HomeController::class, 'article']);
