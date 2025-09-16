@extends('mails.layout')

@section('content')
    Hi {{ $appointment->host->name }},<br>

    Ada Appointment baru nih:<br>
    Nama: {{ $appointment->name }} <br>
    Email: {{ $appointment->email }} <br>
    No Handphone: {{ $appointment->phone_number }} <br>
    Waktu: {{ $appointment->date ." ". $appointment->time_start ."-". $appointment->time_end }} <br>
    Note: {{ $appointment->note }} <br>
    <br>

    <strong>Pre Question:</strong>
        <ul>
            @foreach($appointment->prequestionAnswers as $answer)
                <li>{{ $answer->question->question . ": ". $answer->answer }}</li>
            @endforeach
        </ul>
    <br>
    @if($appointment->status == \App\Enums\AppointmentStatus::Upcoming)
        Mohon dipersiapkan
    @elseif($appointment->status == \App\Enums\AppointmentStatus::PendingApproval)
        Silahkan buka dashboard untuk approve/reject appointment ini.
    @endif
    <br>
    Klik link berikut jika Anda ingin membatalkan <a href="{{ route('appointment.cancel', [$appointment->uuid]) }}">Cancel Appointment</a>

@endsection
