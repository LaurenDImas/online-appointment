<?php

namespace App\Services;

use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ExploreService
{
    public function getServiceType(): Collection
    {
        return cache()->remember('service_types', 3600, function () {
            return ServiceType::all();
        });
    }

    public function exploreHost(array $payload){
        $query = User::with(['hostDetail'])
                    ->whereHas('hostDetail',  function ($subQuery) use ($payload) {
                        $subQuery->where('is_public', true);

                        $subQuery->when(isset($payload['service_type_id']), function ($subQuery) use ($payload) {
                            $subQuery->where('service_type_id', ServiceType::whereUuid($payload['service_type_id'])->first()->id);
                        });

                        $subQuery->when(isset($payload['location']), function ($subQuery) use ($payload) {
                            $subQuery->where('meet_location', 'LIKE', "%{$payload['location']}%");
                        });
                    })
                    ->when(isset($payload['name']), function ($subQuery) use ($payload) {
                        $subQuery->where('name', 'LIKE', "%{$payload['name']}%");
                    });

        return $query->paginate();
    }

    public function getHost(User $host): User
    {
        $host->load([
            'hostDetail',
            'availabilities:host_id,day,time_start,time_end',
            'appointments' => fn($subQuery) => $subQuery
                ->select('host_id','date','time_start','time_end')
                ->whereDate('date', '>=', now()),
            'leaves' => fn($subQuery) => $subQuery
                ->select('host_id','start_date','end_date')
                ->whereDate('start_date', '>=', now()),
            'prequestions:uuid,host_id,question']);
        return $host;
    }
}
