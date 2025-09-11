<?php

namespace App\Models\Appointment;

use Illuminate\Database\Eloquent\Model;

class PrequestionAnswer extends Model
{
    protected $fillable = [
        'uuid',
        'appointment_id',
        'question_id',
        'answer'
    ];
}
