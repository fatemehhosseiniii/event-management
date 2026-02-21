<?php

namespace App\Http\Middleware;

use App\Services\Response as ServicesResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user || !$user->is_admin) {
            return ServicesResponse::error('Forbidden', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
