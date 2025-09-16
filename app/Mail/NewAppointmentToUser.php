<?php

namespace App\Mail;

use App\Enums\AppointmentStatus;
use App\Models\Appointment\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class NewAppointmentToUser extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Appointment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.new-appointment-to-user',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->appointment->status == AppointmentStatus::Upcoming) {
            $calendar = Calendar::create('Appointment bersama '. $this->appointment->host->name);
            $calendar->event(Event::create()
                ->name("Appointment bersama ". $this->appointment->host->name)
                ->startsAt(Carbon::parse($this->appointment->date .' '. $this->appointment->time_start))
                ->endsAt(Carbon::parse($this->appointment->date .' '. $this->appointment->time_end))
            )->withoutTimezone();
            return [
                Attachment::fromData(function () use ($calendar) {
                    return $calendar->get();
                }, 'appointment.ics')->withMime('text/calendar')
            ];
        }
        return [];
    }
}
