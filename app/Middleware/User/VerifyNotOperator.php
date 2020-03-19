<?php

namespace App\Middleware\User;

use App\RoleUser;
use Closure;

class VerifyNotOperator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            is_null(\Auth::user()) ||
            (\Auth::user()->roles()->get()[0]->name === 'operator')
        ) {
            return redirect()->route('operator-platform.dashboard');
        }

        return $next($request);
    }
}
