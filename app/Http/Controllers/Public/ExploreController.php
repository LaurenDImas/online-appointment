<?php

namespace App\Http\Controllers\Public;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\PublicHostDetailResource;
use App\Http\Resources\PublicHostExcerptResource;
use App\Http\Resources\ServiceTypeResource;
use App\Models\User;
use App\Services\ExploreService;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    protected ExploreService $exploreService;

    public function __construct(ExploreService $exploreService){
        $this->exploreService = $exploreService;
    }

    public function gerServiceType(Request $request): \Illuminate\Http\JsonResponse
    {
        $serviceTypes = $this->exploreService->getServiceType();

        return ResponseFormatter::success(ServiceTypeResource::collection($serviceTypes));
    }

    public function exploreHost()
    {
        $posts = $this->exploreService->exploreHost(request()->only(['service_type_id', 'name', 'location']));

        return ResponseFormatter::success(PublicHostExcerptResource::collection($posts)->through(fn ($data) => $data));
    }

    public function detailHost(User $user){
        $host = $this->exploreService->getHost($user);

        return ResponseFormatter::success(new PublicHostDetailResource($host));
    }
}
