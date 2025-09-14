<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PublicHostService;
use Illuminate\Http\Request;

class HostController extends Controller
{
    protected PublicHostService $publicHostService;
    public function __construct(PublicHostService $publicHostService){
        $this->publicHostService = $publicHostService;
    }

    public function getHostCalender(User $user)
    {
        return $this->publicHostService->generateCalendarForHost($user);
    }
}
