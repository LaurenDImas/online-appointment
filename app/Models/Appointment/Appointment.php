<?php

namespace App\Models\Appointment;

use App\Enums\AppointmentStatus;
use App\Models\User;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'host_id',
        'name',
        'email',
        'phone_number',
        'date',
        'time_start',
        'time_end',
        'note',
        'status', // (pending_approval, upcoming, rejected, canceled)
    ];

    protected $casts = [
      'status' => AppointmentStatus::class,
    ];

    protected static function newFactory(): AppointmentFactory
    {
        return AppointmentFactory::new();
    }

    public function host() {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function prequestionAnswers(){
        return $this->hasMany(PrequestionAnswer::class);
    }
}
