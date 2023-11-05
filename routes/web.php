<?php

use App\Controllers\ClassroomController;
use App\Controllers\DashboardController;
use App\Controllers\EmployeeController;
use App\Controllers\ScheduleController;
use App\Controllers\SubjectController;
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

    Route::post('/classrooms', [ClassroomController::class, 'store']);
    Route::put('/classrooms/{id}', [ClassroomController::class, 'update']);
    Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy']);

    // Subject Management
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/create', [SubjectController::class, 'create']);

    Route::post('/subjects', [SubjectController::class, 'store']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

    // Schedule Management
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::get('/schedules/{classId}/json', [ScheduleController::class, 'json']);
});
