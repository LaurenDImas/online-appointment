<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Models\User;
use App\Helpers;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    protected $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed',
            'username' => 'required|min:5|max:100|unique:host_details,username',
            'service_type' => 'required|exists:service_types,uuid',
            'meet_location' => 'required|max:100',
            'meet_timezone' => 'required|max:2',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $payload = $validator->validated();

        $this->authenticationService->registerUser($payload);

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

        $user = User::where('email', request()->email)->whereNull('email_verified_at')->first();

        if (is_null($user)) {
            return ResponseFormatter::error(HttpCode::NOT_FOUND, null, [
                'User tidak ditemukan!'
            ]);
        }

        if (!is_null($user->email_verified_at)) {
            return ResponseFormatter::error(HttpCode::CONFLICT, null, [
                'User sudah terverifikasi!'
            ]);
        }

        $this->authenticationService->resendOtp($user);


        return ResponseFormatter::success([
            'is_sent' => true
        ]);
    }

    public function verifyOtp(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        if($this->authenticationService->verifyOtp(request()->email, request()->otp)) {
            return ResponseFormatter::success([
                'is_correct' => true
            ]);
        };

        return ResponseFormatter::error(HttpCode::BAD_REQUEST, null, ['invalid otp']);
    }

    public function verifyRegister(): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        if($token = $this->authenticationService->verifyRegister(request()->email, request()->otp)) {
            return ResponseFormatter::success([
                'token' => $token
            ]);
        };

        return ResponseFormatter::error(HttpCode::BAD_REQUEST, null, ['invalid otp']);
    }

    public function login(): \Illuminate\Http\JsonResponse
    {
        if($token = $this->authenticationService->login(request()->email, request()->password)) {
            return ResponseFormatter::success([
                'token' => $token
            ]);
        };

        return ResponseFormatter::error(HttpCode::BAD_REQUEST, null, [
            'Email atau Password salah!'
        ]);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth("sanctum")->user()->tokens()->delete();
        return ResponseFormatter::success([
            'logout_success' => true
        ]);
    }
}
