<?php

use App\Controllers\ArticleController;
use System\Utils\Route;
use App\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/article/{id}', [ArticleController::class, 'show']);
