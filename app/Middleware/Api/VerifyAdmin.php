<?php

namespace App\Middleware\Api;

use Closure;

class VerifyAdmin
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json('You are not an admin', 401);
        }

        return $next($request);
    }
}
