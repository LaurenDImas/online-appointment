<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HostAppointmentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'date' => $this->date,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'note' => $this->note,
            'status' => $this->status,
            'prequestion_answers' => AppointmentAnswerResource::collection($this->prequestionAnswers),
            'submitted_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
