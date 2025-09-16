<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PublicAppointmentResource extends JsonResource
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
           'host' => $this->host->only(['uuid', 'name']),
           'name' => $this->name,
           'email' => Str::substr($this->email, 0, 3). "***",
           'phone_number' => Str::substr($this->phone_number, -4). "***",
           'date' => $this->date,
           'time_start' => $this->time_start,
           'time_end' => $this->time_end,
           'note' => $this->note,
           'status' => $this->status,
       ];
    }
}
