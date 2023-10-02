<?php

use App\Controllers\ArticleController;
use System\Components\Route;
use App\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/article', [ArticleController::class, 'create']);
Route::post('/article', [ArticleController::class, 'store']);
Route::get('/article/{id}', [ArticleController::class, 'show']);
