@extends('mails.layout')

@section('content')
    Hi {{ $user->name }},<br>

    Anda melakukan request lupa password, berikut OTP Anda: {{ $otp }}<br>
    Abaikan jika Anda tidak melakukannya.
@endsection
