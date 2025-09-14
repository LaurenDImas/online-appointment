<?php
namespace App\Services;

use App\Models\User;

class LeaveService
{
    public function hasTimeConflict(User $host, string $startDate, string $endDate, ?string $exludeUuid = null)
    {
        $checkQuery = $host->leaves()
            ->where(function ($subQuery) use ($startDate, $endDate) {
                $subQuery->where(function ($subSubQuery) use ($startDate, $endDate) {
                            $subSubQuery->where('start_date', '<=', $endDate)
                                ->where('end_date', '>=', $startDate);
                        });
            });

        if(!is_null($exludeUuid)) {
            $checkQuery->whereNot('uuid', $exludeUuid);
        }

        return $checkQuery->exists();
    }

    public function getLeaves(User $host){
        return $host->leaves()->get();
    }

    public function upsert(User $host, array $leaves){
        $result = collect();
        foreach($leaves as $payload){
            $uuid = $payload['uuid'] ?? null;
            if(!is_null($uuid)){
                $leave = $host->leaves()->where('uuid', $uuid)->first();
                $leave->update($payload);
            }else{
                $leave = $host->leaves()->create($payload);
                $leave->refresh();
            }
            $result->push($leave);
        }

        $host->leaves()->whereNotIn('uuid', $result->pluck('uuid'))->delete();

        return $result;
    }
}
