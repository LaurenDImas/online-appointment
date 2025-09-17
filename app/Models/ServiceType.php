<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ServiceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(function () {
            Cache::forget('service_types');
        });

        static::updated(function () {
            Cache::forget('service_types');
        });

        static::deleted(function () {
            Cache::forget('service_types');
        });

    }
}
