<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicHostDetailResource extends JsonResource
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
            'status' => $this->hostDetail->status,
            'username' => $this->hostDetail->username,
            'service_type' => $this->hostDetail->serviceType->only([
                'uuid',
                'name'
            ]),
            'profile_photo' =>  $this->hostDetail->profile_photo ? asset('storage/'. $this->hostDetail->profile_photo) : null,
            'is_available' => (boolean) $this->hostDetail->is_available,
            'meet_location' => $this->hostDetail->meet_location,
            'meet_timezone' => $this->hostDetail->meet_timezone,
            'availabilities' => $this->availabilities->map(fn($q) => $q->only('day','time_start','time_end')),
            'booked_times' => $this->appointments->map(fn($q) => $q->only('date','time_start','time_end')),
            'leaves' => $this->leaves->map(fn($q) => $q->only('start_date','end_date')),
            'prequestions' => $this->prequestions->map(fn($q) => $q->only('uuid','question')),
        ];
    }
}
