<?php

namespace App\Middleware\Api;

use Closure;

class VerifyOperator
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->isOperator()) {
            return response()->json('You are not an operator', 401);
        }

        return $next($request);
    }
}
