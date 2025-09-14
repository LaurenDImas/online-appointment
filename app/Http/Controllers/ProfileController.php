<?php

namespace App\Http\Controllers;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SummaryResource;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService){
        $this->profileService = $profileService;
    }

    /**
     * @return JsonResponse
     */
    public function getSummary()
    {
        $user = auth()->user();

        return ResponseFormatter::success(new SummaryResource($user));
    }

    /**
     * @return JsonResponse
     */
    public function getProfile()
    {
        $user = auth()->user();

        return ResponseFormatter::success(new ProfileResource($user));
    }

    /**
     * @return JsonResponse
     */
    public function updateProfile() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:5|max:100',
            'email' => 'required|email|unique:users,email,'. auth()->user()->id,
            'password' => 'nullable|min:3|confirmed',
            'username' => 'required|min:5|max:100|unique:host_details,username,'. auth()->user()->id,
            'service_type' => 'required|exists:service_types,uuid',
            'meet_location' => 'required|max:100',
            'meet_timezone' => 'required|max:2',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'is_available' => 'required|boolean',
            'is_public' => 'required|boolean',
            'is_auto_approve' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(HttpCode::BAD_REQUEST, $validator->errors());
        }

        $payload = $validator->validated();
        $user = $this->profileService->updateProfile(auth()->user(), $payload);

        return ResponseFormatter::success(new ProfileResource($user));
    }
}
