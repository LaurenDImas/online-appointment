<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment\Appointment;
use App\Models\User;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class PublicHostService
{
    public function generateCalendarForHost(User $host)
    {

        $calendarBuilder = Calendar::create($host->name . "'s Appointment")->withoutTimezone();
        $appointments = $host->appointments()
            ->where('status', AppointmentStatus::Upcoming)
            ->whereDate('date', ">=" , now()->subDay(7))
            ->whereDate('date', '<=', now()->addYear(1))
            ->get()->each(function ($appointment) use ($calendarBuilder) {
                $calendarBuilder->event(Event::create('Appointment: '. $appointment->name)
                    ->startsAt(\Carbon\Carbon::parse($appointment->date. ' ' . $appointment->time_start))
                    ->endsAt(\Carbon\Carbon::parse($appointment->date. ' ' . $appointment->time_end))
                    ->attendee($appointment->email, $appointment->name)
                );
            });
        return $calendarBuilder->get();
    }
}
