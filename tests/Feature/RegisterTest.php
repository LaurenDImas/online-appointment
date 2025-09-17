<?php

use App\Mail\SendRegisterOtp;
use App\Models\Otp;
use App\Models\ServiceType;
use Database\Seeders\ServiceTypeSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
});

test('register', function () {
    Mail::fake();

    $this->assertDatabaseCount("otps", 0);
    $response = $this->postJson('/api/v1/register', [
        "name" => "Dimas Yogi",
        "email" => "dimas.yogi@gmail.com",
        "password" => "password",
        "password_confirmation" => "password",
        "username" => "dimasyogi",
        "service_type" => generateServiceType()->uuid,
        "meet_location" => "Gedung MidPlaza lt.10",
        "meet_timezone" => 7
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'meta' => [
            'code',
            'status',
            'messages',
        ],
        'data' => [
            'is_sent'
        ]
    ]);
    $this->assertDatabaseHas("users", [
        "email" => "dimas.yogi@gmail.com"
    ]);
    $this->assertDatabaseCount("otps", 1);

    Mail::assertQueued(SendRegisterOtp::class, function ($email){
        return $email->hasTo("dimas.yogi@gmail.com");
    });
});


test('register until success', function () {
    $response = $this->postJson('/api/v1/register', [
        "name" => "Dimas Yogi",
        "email" => "dimas.yogi1@gmail.com",
        "password" => "password",
        "password_confirmation" => "password",
        "username" => "dimasyogi1",
        "service_type" => generateServiceType()->uuid,
        "meet_location" => "Gedung MidPlaza lt.10",
        "meet_timezone" => 7
    ]);

    $response->assertStatus(200)->assertJsonStructure([
        'meta' => [
            'code',
            'status',
            'messages',
        ],
        'data' => [
            'is_sent'
        ]
    ]);

    $otp = Otp::first();
    $response1 = $this->postJson('/api/v1/verify-register', [
        "email" => "dimas.yogi1@gmail.com",
        "otp" => $otp->otp
    ]);
    $response1->assertJsonStructure([
        "data" => [
            'token'
        ]
    ]);
});


test('register with same email', function () {
    $payload = [
        "name" => "Dimas Yogi",
        "email" => "dimas.yogi@gmail.com",
        "password" => "password",
        "password_confirmation" => "password",
        "username" => "dimasyogi",
        "service_type" => generateServiceType()->uuid,
        "meet_location" => "Gedung MidPlaza lt.10",
        "meet_timezone" => 7
    ];

    $this->postJson('/api/v1/register', $payload);

    $response = $this->postJson('/api/v1/register', $payload);

    $response->assertStatus(400);
    $this->assertDatabaseCount("users", 1);
    $this->assertDatabaseCount("otps", 1);
});
