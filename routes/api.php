<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/resend-otp', [AuthenticationController::class, 'resendOTP']);
    Route::post('/check-otp-register', [AuthenticationController::class, 'verifyOtp']);
    Route::post('/verify-register', [AuthenticationController::class, 'verifyRegister']);
});
