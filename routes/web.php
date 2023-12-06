<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SubjectController;
use System\Components\Route;

require_once __DIR__ . '/auth.php';

Route::middleware('auth:web', function() {
    Route::get('/', [HomeController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/chart', [DashboardController::class, 'chartJson']);

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
    Route::get('/schedules/{classId}/create', [ScheduleController::class, 'create']);

    Route::post('/schedules/{classId}', [ScheduleController::class, 'store']);
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

    // Attendances Management
    Route::get('/attendances', [AttendanceController::class, 'index']);
    Route::get('/attendances/daily', [AttendanceController::class, 'dailyJson']);
    Route::get('/attendances/all', [AttendanceController::class, 'allJson']);
    Route::get('/attendances/{id}', [AttendanceController::class, 'detailJson']);

    // Holidays
    Route::get('/holidays', [HolidayController::class, 'index']);
    Route::get('/holidays/{id}/json', [HolidayController::class, 'show']);

    Route::post('/holidays/store', [HolidayController::class, 'store']);
    Route::post('/holidays/json', [HolidayController::class, 'json']);
    Route::put('/holidays/{id}', [HolidayController::class, 'update']);
    Route::delete('/holidays/{id}', [HolidayController::class, 'destroy']);
});
