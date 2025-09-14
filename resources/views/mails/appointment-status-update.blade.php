@extends('mails.layout')

@section('content')
    Hi {{ $appointment->name }},<br>
    @if($appointment->status == \App\Enums\AppointmentStatus::Upcoming)
        Appointment Anda pada {{ $appointment->date .' '. $appointment->time_start  }} dengan {{ $appointment->host->name  }} telah diterima, silahkan data sesuai janji
    @else
        Appointment Anda pada {{ $appointment->date .' '. $appointment->time_start  }} dengan {{ $appointment->host->name  }} telah ditolak, silahkan data sesuai janji
    @endif
@endsection
