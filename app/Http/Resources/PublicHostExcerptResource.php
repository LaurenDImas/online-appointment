<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicHostExcerptResource extends JsonResource
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
        ];
    }
}
