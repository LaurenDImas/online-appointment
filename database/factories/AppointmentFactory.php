<?php

namespace Database\Factories;

use App\Models\Appointment\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;  // <--- ini penting supaya factory tahu model mana

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone_number' => fake()->phoneNumber(),
            'date' => now()->addDay(rand(1,10))->format('Y-m-d'),
            'time_start' => '07:00',
            'time_end' => '07:30',
            'note' => fake()->realText(50),
            'status' => 'upcoming',
        ];
    }
}
