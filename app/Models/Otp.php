<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'otpable_type',
        'otpable_id',
        'type', // registration, forgot_password, appointment
        'otp',
        'expires_at',
        'is_active',
    ];
}
