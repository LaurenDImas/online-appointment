@extends('mails.layout')

@section('content')
    Hi {{ $host->name }},<br>

    Berikut jadwal Appointment Anda hari ini: <br>
    <ul>
        @foreach($appointments as $appointment)
            <li>{{ $appointment->name ." pada " . $appointment->time_start . " - " . $appointment->time_end  }}</li>
        @endforeach
    </ul>
@endsection
