<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Mail\AppointmentStatusUpdate;
use App\Models\Appointment\Appointment;
use Illuminate\Support\Facades\Mail;

class HostAppointmentService
{
    public function updateStatus(Appointment $appointment, AppointmentStatus $status): Appointment
    {
        $appointment->update(['status' => $status]);
        $appointment->refresh();

        Mail::to($appointment->email)->send(new AppointmentStatusUpdate($appointment));
        return $appointment;
    }
}
