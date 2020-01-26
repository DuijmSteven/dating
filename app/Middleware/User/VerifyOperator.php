<?php

namespace App\Middleware\User;

use App\RoleUser;
use Closure;

class VerifyOperator
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
        if (is_null(\Auth::user()) || !in_array(\Auth::user()->roles()->get()[0]->name, ['admin', 'operator'])) {
            return redirect()->route('login.show');
        }

        return $next($request);
    }
}
