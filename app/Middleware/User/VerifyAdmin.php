<?php

namespace App\Middleware\User;

use App\RoleUser;
use Closure;

class VerifyAdmin
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
        \Log::info(\Auth::user()->roles()->get());

        if (is_null(\Auth::user()) || \Auth::user()->roles()->get()[0]->name !== 'admin') {
            return redirect()->route('login.get');
        }

        return $next($request);
    }
}
