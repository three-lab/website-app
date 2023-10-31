<?php

use App\Controllers\ClassroomController;
use App\Controllers\DashboardController;
use App\Controllers\EmployeeController;
use System\Components\Route;

require_once __DIR__ . '/auth.php';

Route::middleware('auth:web', function() {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Employee Management
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/create', [EmployeeController::class, 'create']);
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);

    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

    // Classroom Management
    Route::get('/classrooms', [ClassroomController::class, 'index']);
    Route::get('/classrooms/create', [ClassroomController::class, 'create']);
    Route::get('/classrooms/{id}/edit', [ClassroomController::class, 'edit']);
});
