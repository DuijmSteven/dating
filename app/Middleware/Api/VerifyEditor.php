<?php

namespace App\Middleware\Api;

use Closure;

class VerifyEditor
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isEditor()) {
            return response()->json('You are not an editor', 401);
        }

        return $next($request);
    }
}
