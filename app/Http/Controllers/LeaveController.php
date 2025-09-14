<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\LeaveResource;
use App\Services\LeaveService;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    protected LeaveService $leaveService;
    public function __construct(LeaveService $leaveService){
        $this->leaveService = $leaveService;
    }

    public function index(){
        $leaves = $this->leaveService->getLeaves(auth()->user());

        return ResponseFormatter::success(LeaveResource::collection($leaves),'Leave List');
    }
    public function upsert(){
        $validator = Validator::make(request()->all(),[
            'leaves' => 'required|array',
            'leaves.*.uuid' => 'nullable|exists:leaves,uuid',
            'leaves.*.start_date' => 'required|date_format:Y-m-d',
            'leaves.*.end_date' => 'required|date_format:Y-m-d',
        ]);

        if($validator->fails()){
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,$validator->errors());
        }

        $payload = $validator->validated()['leaves'];
        $user = auth()->user();
        foreach($payload as $key => $item){
            if($this->leaveService->hasTimeConflict(
                $user,
                $item['start_date'],
                $item['end_date'],
                $item['uuid'] ?? null
            )){
                return ResponseFormatter::error(HttpCode::BAD_REQUEST,[],[
                    'Waktu bentrok pada item item ke-'. ($key+1)
                ]);
            }
        }

        $updateLeaves = $this->leaveService->upsert($user, $payload);

        return ResponseFormatter::success(LeaveResource::collection($updateLeaves),'Leave Updated');
    }
}
