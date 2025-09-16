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
@endsection
