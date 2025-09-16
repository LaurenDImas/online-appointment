<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Mail\NewAppointmentToHost;
use App\Mail\NewAppointmentToUser;
use App\Models\Appointment\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PublicAppointmentService
{
    public function checkAvailabilityForAppointment(User $host, string $date, string $timeStart, string $timeEnd): bool|string
    {
        // TODO: Status Host
        if ($host->hostDetail->status != 'active'){
            return "Status host sedang tidak aktif";
        }
        // TODO: Check Leave
        $checkLeaves = $host->leaves()->whereDate('start_date','<=',$date)
                                    ->whereDate('end_date','>=', $date)
                                    ->exists();
        if ($checkLeaves){
            return "Host sedang cuti pada tanggal tersebut !";
        }
        // TODO: Check Availability
        $selectedDate = Carbon::parse($date);
        $checkAvailability = $host->availabilities()->where('day', $selectedDate->dayOfWeekIso)
                                    ->whereTime('time_start', '<=', $timeEnd)
                                    ->whereTime('time_end', '>=', $timeStart)->exists();
        if (!$checkAvailability){
            return "Hari tidak tersedia pada tanggal dan jam yang dipilih";
        }
        // TODO: Check Other APPOINTMENT
        $checkOtherAppointment = $host->appointments()->where('status', AppointmentStatus::Upcoming)
            ->where('date', $date)
            ->whereTime('time_start', '<=', $timeEnd)
            ->whereTime('time_end', '>=', $timeStart)->exists();

        if ($checkOtherAppointment){
            return "Host sedang ada janji lain pada tanggal dan jam yang dipilih";
        }

        return "";
    }

    public function createAppointment(User $host, array $payload)
    {
        $appointment = DB::transaction(function () use ($host, $payload) {
            $answers = $payload['answers'];
            unset($payload['answers']);
            if(!$host->hostDetail->is_auto_approve){
                $payload['status'] = AppointmentStatus::PendingApproval;
            }
            $appointment = $host->appointments()->create($payload);
            foreach ($answers as $key => $answer) {
                $appointment->prequestionAnswers()->create([
                    'question_id' => $host->prequestions()->where('uuid', $answer['uuid'])->first()->id,
                    'answer' => $answer['answer'],
                ]);
            }

            $appointment->refresh();
            Mail::to($host->email)->send(new NewAppointmentToHost($appointment));
            Mail::to($appointment->email)->send(new NewAppointmentToUser($appointment));
            return $appointment;
        });
        return $appointment;
    }

    public function cancelAppointment(Appointment $appointment): Appointment
    {
        $appointment->update([
            'status' => AppointmentStatus::Canceled
        ]);
        return $appointment;
    }
}
