<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;

// Публичные маршруты
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);

// Защищённые маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('news', NewsController::class);
    Route::apiResource('staff', StaffController::class);
    Route::apiResource('services', ServiceController::class)->except(['index']);
    Route::apiResource('schedules', ScheduleController::class)->except(['index']);
    
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
    Route::post('/schedules/{id}/book', [ScheduleController::class, 'book']);
});