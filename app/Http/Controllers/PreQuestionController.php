<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\PreQuestionResource;
use App\Services\PreQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreQuestionController extends Controller
{
    protected PreQuestionService $preQuestionService;
    public function __construct(PreQuestionService $preQuestionService){
        $this->preQuestionService = $preQuestionService;
    }

    public function index(){
        $availabilities = $this->preQuestionService->getPrequestions(auth()->user());

        return ResponseFormatter::success(PreQuestionResource::collection($availabilities),'PreQuestion List');
    }
    public function upsert(){
        $validator = Validator::make(request()->all(),[
            'prequestions' => 'required|array',
            'prequestions.*.uuid' => 'nullable|exists:prequestions,uuid',
            'prequestions.*.question' => 'required|min:5|max:255',
        ]);

        if($validator->fails()){
            return ResponseFormatter::error(HttpCode::BAD_REQUEST,$validator->errors());
        }

        $payload = $validator->validated()['prequestions'];
        $user = auth()->user();
        $updatedPrequestions = $this->preQuestionService->upsert($user, $payload);

        return ResponseFormatter::success(PreQuestionResource::collection($updatedPrequestions),'PreQuestion Updated');
    }
}
