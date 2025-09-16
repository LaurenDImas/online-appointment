<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PreQuestionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Public\ExploreController;
use App\Http\Controllers\Public\BookController;

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

    Route::prefix('public')->group(function () {
        Route::get('service-type', [ExploreController::class, 'gerServiceType']);
        Route::get('explore', [ExploreController::class, 'exploreHost']);
        Route::get('explore/{user:uuid}', [ExploreController::class, 'detailHost']);

        Route::post('explore/{user:uuid}/book', [BookController::class, 'book']);
        Route::get('appointment/{appointment:uuid}', [BookController::class, 'detailAppointment']);

        Route::post('appointment/{appointment:uuid}/cancel', [BookController::class, 'cancelAppointment'])->name('appointment.cancel');
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

        Route::get('availabilities', [AvailabilityController::class, 'index']);
        Route::post('availabilities', [AvailabilityController::class, 'upsert']);

        Route::get('leaves', [LeaveController::class, 'index']);
        Route::post('leaves', [LeaveController::class, 'upsert']);

        Route::get('appointments', [AppointmentController::class, 'index']);
        Route::get('appointments/{appointment:uuid}', [AppointmentController::class, 'show']);
        Route::post('appointments/{appointment:uuid}/{status}', [AppointmentController::class, 'updateStatus']);
    });
});
