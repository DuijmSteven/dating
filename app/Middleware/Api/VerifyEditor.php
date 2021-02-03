<?php

namespace App\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

class VerifyEditor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isEditor()) {
            return response()->json('You are not an editor', 401);
        }

        return $next($request);
    }
}
