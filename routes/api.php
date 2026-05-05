<?php

use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ContactController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/services', function () {
    return Service::all();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('news', NewsController::class);
    Route::apiResource('staff', StaffController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('bookings', BookingController::class); // ТОЛЬКО ТАК
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/schedules/book', [ScheduleController::class, 'book']);
Route::post('/contacts', [ContactController::class, 'store']);