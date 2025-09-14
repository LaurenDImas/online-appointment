<?php

namespace Database\Seeders;

use App\Models\Appointment\Appointment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::with('prequestions')->first();
        $appointment = Appointment::factory()->create([
            "host_id" => $user->id,
        ]);
        foreach ($user->prequestions as $question) {
            $appointment->prequestionAnswers()->create([
               'question_id' => $question->id,
               'answer' => fake()->realText(50),
            ]);
        }
    }
}
