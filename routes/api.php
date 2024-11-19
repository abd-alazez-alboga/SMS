<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OTPController;

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-otp', [OTPController::class, 'sendOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);

// protected routes for regular users
Route::middleware(['auth:sanctum', 'two_factor'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logout']);
    // user details
    Route::get('/user', [AuthController::class, 'user']);
    // trip
    Route::get('/trips', [TripController::class, 'index']);
    Route::get('/trips/{id}', [TripController::class, 'show']);
    // newsfeed
    Route::get('/newsfeeds', [NewsfeedController::class, 'index']);
    Route::get('/newsfeeds/{id}', [NewsfeedController::class, 'show']);
    // booking
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings', [BookingController::class, 'store']);
});

// ---------------------------------------- Admin Routes ----------------------------------------
// protected routes for admin users
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    // trip
    Route::post('/trips', [TripController::class, 'store']);
    Route::put('/trips/{id}', [TripController::class, 'update']);
    Route::delete('/trips/{id}', [TripController::class, 'destroy']);
    // newsfeed
    Route::post('/newsfeeds', [NewsfeedController::class, 'store']);
    Route::put('/newsfeeds/{id}', [NewsfeedController::class, 'update']);
    Route::delete('/newsfeeds/{id}', [NewsfeedController::class, 'destroy']);
    // booking
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);
});
