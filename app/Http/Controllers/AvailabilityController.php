<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Http\Resources\AvailabilityResource;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;

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

    }
}
