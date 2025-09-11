<?php

namespace App\Models\Host;

use Illuminate\Database\Eloquent\Model;

class HostDetail extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'username',
        'service_type_id',
        'profile_photo',
        'is_available',
        'meet_location',
        'meet_timezone',
        'is_public',
        'is_auto_approve',
    ];
}
