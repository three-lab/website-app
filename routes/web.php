<?php

use App\Controllers\DashboardController;
use App\Controllers\EmployeeController;
use System\Components\Route;

require_once __DIR__ . '/auth.php';

Route::middleware('auth:web', function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/create', [EmployeeController::class, 'create']);
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);

    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);
});
