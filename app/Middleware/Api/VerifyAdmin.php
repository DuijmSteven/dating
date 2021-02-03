<?php

namespace App\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class VerifyAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json('You are not an admin', 401);
        }

        return $next($request);
    }
}
