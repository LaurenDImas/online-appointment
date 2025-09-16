<?php

namespace App\Console\Commands;

use App\Enums\AppointmentStatus;
use App\Mail\DailyReminderToHost;
use App\Mail\ReminderToGuest;
use App\Models\Appointment\Appointment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Senda daily reminder to host & guest';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        User::has('hostDetail')
            ->withWhereHas('appointments', function ($subQuery) {
                $subQuery->where('status', AppointmentStatus::Upcoming)
                        ->whereDate('date', now()->format('Y-m-d'));
            })->get()->each(function ($host) {
                $appointments = $host->appointments;
                Mail::to($host->email)->send(new DailyReminderToHost($host, $appointments));
            });

        Appointment::where('status', AppointmentStatus::Upcoming)
            ->whereDate('date', now()->format('Y-m-d'))
            ->get()->each(function ($appointment) {
               Mail::to($appointment->email)->send(new ReminderToGuest($appointment));
            });

    }
}
