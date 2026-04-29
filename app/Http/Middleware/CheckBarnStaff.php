<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBarnStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->user_type !== 'barn_staff') {
            abort(403, 'Access denied. Only barn staff can access this page.');
        }

        return $next($request);
    }
}