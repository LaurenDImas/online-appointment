<?php

namespace App\Http\Middleware;

use App\Enums\HttpCode;
use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SanctumGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('sanctum');
        if (!auth()->check()) {
            return ResponseFormatter::error(HttpCode::UNAUTHORIZED, [], "Unauthorized");
        }

        return $next($request);
    }
}
