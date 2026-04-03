<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LocationController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::get('/countries', [LocationController::class, 'countries']);
    Route::get('/regions', [LocationController::class, 'regions']);
    
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    
    // Password Reset
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');

    // Email Verification (Publicly accessible but throttled)
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/email/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
    });

    Route::get('/test', function () {
        return response()->json([
            'message' => 'Test successful',
        ]);
    });

    // Protected routes (require valid token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        // Add more routes here as needed
    });
});
