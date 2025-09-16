@extends('mails.layout')

@section('content')
    Hi {{ $appointment->name }},<br>

    <br>
    Nama Host: {{ $appointment->host->name }} <br>
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
        Silahkan datang tepat waktu
    @elseif($appointment->status == \App\Enums\AppointmentStatus::PendingApproval)
        Mohon tunggu approval dari host
    @endif

    <br>

    Klik link berikut jika Anda ingin membatalkan <a href="{{ route('appointment.cancel', [$appointment->uuid]) }}">Cancel Appointment</a>
@endsection
