<?php

use App\Controllers\Api\AuthController;
use System\Components\Route;

Route::post('/login', [AuthController::class, 'login']);
