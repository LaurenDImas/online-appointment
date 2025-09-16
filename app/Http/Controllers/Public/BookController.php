<?php

namespace App\Http\Controllers\Public;

use App\Enums\AppointmentStatus;
use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicAppointmentResource;
use App\Models\Appointment\Appointment;
use App\Models\User;
use App\Services\PublicAppointmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    protected PublicAppointmentService $publicAppointmentService;
    public function __construct(PublicAppointmentService $publicAppointmentService){
        $this->publicAppointmentService = $publicAppointmentService;
    }

    public function book(User $user): \Illuminate\Http\JsonResponse
    {
        $user->load(['hostDetail','prequestions','leaves']);
        if(is_null($user->hostDetail)){
            return ResponseFormatter::error(HttpCode::NOT_FOUND);
        }

        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:5|max:50',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d|after:today',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'note' => 'nullable|max:255',
            'answers' => 'array'. $user->prequestions()->exists() ? '|required' : '|nullable',
            'answers.*.uuid' => 'required|exists:prequestions,uuid',
            'answers.*.answer' => 'required|min:1|max:2000',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $payload = $validator->validated();

        if($message = $this->publicAppointmentService->checkAvailabilityForAppointment(
            $user,
            $payload['date'],
            $payload['time_start'],
            $payload['time_end'])) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, [], $message);
        }

        $appointment = $this->publicAppointmentService->createAppointment($user, $payload);

        return ResponseFormatter::success($appointment);
    }

    public function detailAppointment(Appointment $appointment)
    {
        return ResponseFormatter::success(new PublicAppointmentResource($appointment));
    }

    public function cancelAppointment(Appointment $appointment): \Illuminate\Http\JsonResponse
    {
        if ($appointment->status == AppointmentStatus::Canceled){
            return ResponseFormatter::error(HttpCode::NOT_FOUND,[], "Appointment has been cancelled");
        }
        if ($appointment->status == AppointmentStatus::Rejected){
            return ResponseFormatter::error(HttpCode::NOT_FOUND,[], "Appointment has been rejected");
        }
        $this->publicAppointmentService->cancelAppointment($appointment);
        return ResponseFormatter::success(new PublicAppointmentResource($appointment));
    }
}
