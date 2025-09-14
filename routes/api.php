<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PreQuestionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('resend-otp', [AuthenticationController::class, 'resendOTP']);
    Route::post('check-otp-register', [AuthenticationController::class, 'verifyOtp']);
    Route::post('verify-register', [AuthenticationController::class, 'verifyRegister']);
    Route::post('login', [AuthenticationController::class, 'login']);

    Route::prefix('forgot-password')->group(function () {
        Route::post('request', [ForgotPasswordController::class, 'request']);
        Route::post('resend-otp', [ForgotPasswordController::class, 'resendOtp']);
        Route::post('check-otp', [ForgotPasswordController::class, 'verifyOtp']);
        Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
    });

    Route::middleware('auth.sanctum.custom')->group(function () {
        Route::post('logout', [AuthenticationController::class, 'logout']);

        Route::get('availabilities', [AvailabilityController::class, 'index']);
        Route::post('availabilities', [AvailabilityController::class, 'upsert']);

        Route::get('summary', [ProfileController::class, 'getSummary']);
        Route::get('profile', [ProfileController::class, 'getProfile']);
        Route::post('profile', [ProfileController::class, 'updateProfile']);

        Route::get('prequestions', [PreQuestionController::class, 'index']);
        Route::post('prequestions', [PreQuestionController::class, 'upsert']);
    });
});
