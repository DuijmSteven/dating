<?php

namespace App\Middleware\User;

use App\RoleUser;
use App\User;
use Closure;
use Illuminate\Support\Str;

class VerifyNotAnonymousDomain
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
            config('app.env') !== 'local' &&
            !Str::contains(request()->getHttpHost(), 'altijdsex') &&
            (is_null(\Auth::user()) || \Auth::user()->roles()->get()[0]->id !== User::TYPE_ADMIN)
        ) {
            return redirect('https://devely-operators.nl');
        }

        return $next($request);
    }
}
