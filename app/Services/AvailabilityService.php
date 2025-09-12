<?php
namespace App\Services;

use App\Models\User;

class AvailabilityService
{
    public function getAvailabilities(User $host){
        return $host->availabilities()->get();
    }
}
