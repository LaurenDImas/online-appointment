<?php
namespace App\Services;

use App\Helpers\ResponseFormatter;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordService{

    public function request(User $user){
        do {
            $otp = rand(100000, 999999);

            $otpCount = Otp::where('otp', $otp)->first();
        } while ($otpCount > 0);

        // TODO: Create OTP
        $user->otps()->create([
            'type' => 'forgot_password',
            'otp' => $otp,
            'expired_at' => now()->addDay(),
            'is_active' => true,
        ]);

        Mail::to($user->email)->send(new \App\Mail\SendForgotPasswordOTP($user, $otp));
    }

    public function resendOtp(User $user): void
    {
        $user->otps()->where('type', 'forgot_password')->delete();
        $this->request($user);
    }

    public function verifyOtp(User $user, $otp): bool
    {
        $otpCheck = $user->otps()->where('type', 'forgot_password')
            ->where('otp', $otp)
            ->where('is_active', true)
            ->where('expired_at', '>=', now())
            ->first();

        if (is_null($otpCheck)) {
            return false;
        }

        $otpCheck->delete();

        return true;
    }

    public function resetPassword(User $user, string $otp, string $newPassword): bool
    {
        $otpCheck = $user->otps()->where('type', 'forgot_password')
            ->where('otp', $otp)
            ->where('is_active', true)
            ->where('expired_at', '>=', now())
            ->first();

        if (is_null($otpCheck)) {
            return false;
        }

        $user->update([
            'password' => bcrypt($newPassword),
        ]);
        return true;
    }
}
