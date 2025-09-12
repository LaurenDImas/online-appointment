<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Models\Otp;
use App\Models\User;
use App\Services\ForgotPasswordService;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected ForgotPasswordService $forgotPasswordService;

    public function __construct(ForgotPasswordService $service)
    {
        $this->forgotPasswordService = $service;
    }

    public function request(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $user = User::whereEmail(request()->email)->first();
        $check = Otp::where('otpable_type', User::class)
                ->where('otpable_id', $user->id)
                ->where('type', 'forgot_password')
                ->exists();

        if ($check) {
            return ResponseFormatter::error(HttpCode::FORBIDDEN, null, [
                'Anda sudah melakukan ini, silahkan resend OTP!'
            ]);
        }
        $this->forgotPasswordService->request($user);

        return ResponseFormatter::success([
            'is_sent' => true
        ]);
    }

    public function resendOtp(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $user = User::whereEmail(request()->email)->first();

        $this->forgotPasswordService->resendOtp($user);

        return ResponseFormatter::success([
            'is_sent' => true
        ]);
    }

    public function verifyOtp(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $user = User::whereEmail(request()->email)->first();

        if($this->forgotPasswordService->verifyOtp($user, request()->otp)) {
            return ResponseFormatter::success(HttpCode::OK, [
                'is_valid' => true
            ]);
        }

        return ResponseFormatter::error(HttpCode::BAD_REQUEST, [], ['Invalid OTP']);
    }

    public function resetPassword(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $user = User::whereEmail(request()->email)->first();
        if($this->forgotPasswordService->resetPassword($user, request()->otp, request()->password)) {
            return ResponseFormatter::success(HttpCode::OK, [
                "reset" => true
            ]);
        }


        return ResponseFormatter::error(HttpCode::BAD_REQUEST, [], ['Invalid OTP']);
    }
}
