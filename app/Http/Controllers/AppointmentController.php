<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\HostAppointmentDetailResource;
use App\Http\Resources\HostAppointmentExcerptResource;
use App\Models\Appointment\Appointment;
use App\Services\HostAppointmentService;
use Illuminate\Http\Request;

class   AppointmentController extends Controller
{
    protected HostAppointmentService $hostAppoinmentService;
    public function __construct(HostAppointmentService $hostAppoinmentService){
        $this->hostAppoinmentService = $hostAppoinmentService;
    }

    public function index(){
        $query = auth()->user()->appointments();
        $appointments = $query->paginate(request('per_page') ?? 10);

        return ResponseFormatter::success(HostAppointmentExcerptResource::collection($appointments)->through(fn($appointment) => $appointment));
    }

    public function show(Appointment $appointment){
        $appointment->load(['prequestionAnswers.question']);
        if ($appointment->host_id != auth()->user()->id) {
            return ResponseFormatter::error(HttpCode::FORBIDDEN);
        }
        return ResponseFormatter::success(new HostAppointmentDetailResource($appointment));
    }

    public function updateStatus(Appointment $appointment, string $status){
        if ($appointment->status != AppointmentStatus::PendingApproval) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,[],[
                "Hanya bisa update status jika masih pending"
            ]);
        }

        if (!in_array($status, [AppointmentStatus::Approved->value,AppointmentStatus::Rejected->value])) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,[],["Status tidak valid"]);
        }

        if ($status == AppointmentStatus::Approved->value) {
            $status = AppointmentStatus::Upcoming;
        }

        if ($appointment->status == $status) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,[],[
                "Status tidak berubah"
            ]);
        }

        $appointment = $this->hostAppoinmentService->updateStatus($appointment, $status);

        return ResponseFormatter::success(new HostAppointmentDetailResource($appointment));
    }
}
