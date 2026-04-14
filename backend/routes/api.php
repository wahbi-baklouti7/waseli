<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\Logistics\TripController;
use App\Http\Controllers\Api\V1\Logistics\DeliveryRequestController;
use App\Models\User;

Route::prefix('v1')->group(function () {
    // Public routes
    // Logistics Reference Data
    Route::get('/locations', [LocationController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/trips', [TripController::class, 'index']);
    Route::get('/trips/{trip}', [TripController::class, 'show']);
    Route::get('/delivery-requests', [DeliveryRequestController::class, 'index']);
    Route::get('/delivery-requests/{delivery_request}', [DeliveryRequestController::class, 'show']);
    
    Route::get('/users', function () {
        return response()->json([
            'message' => 'Users list',
            'users' => User::all(),
        ]);
    });
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
        
        // Logistics
        Route::apiResource('trips', TripController::class)->except(['index', 'show']);
        Route::apiResource('delivery-requests', DeliveryRequestController::class)->except(['index', 'show']);

        // Marketplace Applications
        Route::post('/trips/{trip}/apply', [\App\Http\Controllers\Api\V1\Marketplace\TripRequestController::class, 'store']);
        Route::patch('/trip-requests/{trip_request}/status', [\App\Http\Controllers\Api\V1\Marketplace\TripRequestController::class, 'updateStatus']);

        Route::post('/delivery-requests/{delivery_request}/apply', [\App\Http\Controllers\Api\V1\Marketplace\RequestApplicationController::class, 'store']);
        Route::patch('/request-applications/{request_application}/status', [\App\Http\Controllers\Api\V1\Marketplace\RequestApplicationController::class, 'updateStatus']);

        // Fulfillment
        Route::patch('/trip-requests/{trip_request}/pickup', [\App\Http\Controllers\Api\V1\Fulfillment\DeliveryVerificationController::class, 'pickUp']);
        Route::post('/trip-requests/{trip_request}/verify', [\App\Http\Controllers\Api\V1\Fulfillment\DeliveryVerificationController::class, 'verify']);
    });
});
