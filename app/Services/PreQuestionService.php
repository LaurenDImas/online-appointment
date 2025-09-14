<?php
namespace App\Services;

use App\Models\User;

class PreQuestionService
{
    public function getPrequestions(User $host){
        return $host->prequestions()->get();
    }

    public function upsert(User $host, array $prequestions){
        $result = collect();
        foreach($prequestions as $payload){
            $uuid = $payload['uuid'] ?? null;
            if(!is_null($uuid)){
                $prequestion = $host->prequestions()->where('uuid', $uuid)->first();
                $prequestion->update($payload);
            }else{
                $prequestion = $host->prequestions()->create($payload);
                $prequestion->refresh();
            }
            $result->push($prequestion);
        }

        $host->prequestions()->whereNotIn('uuid', $result->pluck('uuid'))->delete();

        return $result;
    }
}
