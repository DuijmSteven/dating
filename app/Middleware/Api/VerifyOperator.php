<?php

namespace App\Middleware\Api;

use Closure;

class VerifyOperator
{
    public function handle($request, Closure $next)
    {
        if (
            !$request->user()->isOperator() &&
            !$request->user()->isAdmin()
        ) {
            return response()->json('You are not an operator or admin', 401);
        }

        return $next($request);
    }
}
