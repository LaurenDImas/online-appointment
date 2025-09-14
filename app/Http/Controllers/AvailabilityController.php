<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\AvailabilityResource;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailabilityController extends Controller
{
    protected AvailabilityService $availabilityService;
    public function __construct(AvailabilityService $availabilityService){
        $this->availabilityService = $availabilityService;
    }

    public function index(){
        $availabilities = $this->availabilityService->getAvailabilities(auth()->user());

        return ResponseFormatter::success(AvailabilityResource::collection($availabilities),'Availability List');
    }
    public function upsert(){
        $validator = Validator::make(request()->all(),[
            'availabilities' => 'required|array',
            'availabilities.*.uuid' => 'nullable|exists:availabilities,uuid',
            'availabilities.*.day' => 'required|integer|between:1,7',
            'availabilities.*.time_start' => 'required|date_format:H:i',
            'availabilities.*.time_end' => 'required|date_format:H:i',
        ]);

        if($validator->fails()){
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,$validator->errors());
        }

        $payload = $validator->validated()['availabilities'];
        $user = auth()->user();
        foreach($payload as $key => $item){
            if($this->availabilityService->hasTimeConflict(
                $user,
                $item['day'],
                $item['time_start'],
                $item['time_end'],
                $item['uuid'] ?? null
            )){
                return ResponseFormatter::error(HttpCode::BAD_REQUEST,[],[
                    'Waktu bentrok pada item item ke-'. ($key+1)
                ]);
            }
        }

        $updateAvailabilities = $this->availabilityService->upsert($user, $payload);

        return ResponseFormatter::success(AvailabilityResource::collection($updateAvailabilities),'Availability Updated');
    }
}
