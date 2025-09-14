<?php

namespace App\Services;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $host, array $payload): User
    {
        if (isset($payload['password'])) {
            if (is_null($payload['password'])) {
                unset($payload['password']);
            }else{
                $payload['password'] = bcrypt($payload['password']);
            }
        }

        if (isset($payload['profile_photo'])) {
            $payload['profile_photo'] = $payload['profile_photo']->store('user-photo', 'public');
            Storage::disk('public')->delete($host->hostDetail->profile_photo);
        }

        if (isset($payload['service_type'])) {
            $payload['service_type'] = ServiceType::where('uuid', $payload['service_type'])->firstOrFail();
        }

        $payload = collect($payload);
        $host->update($payload->only([
            'name',
            'email',
            'password'
        ])->toArray());

        $host->hostDetail()->update($payload->only([
            'username',
            'service_type_id',
            'profile_photo',
            'is_available',
            'meet_location',
            'meet_timezone',
            'is_public',
            'is_auto_approve',
        ])->toArray());

        $host->refresh();

        return $host;
    }
}
