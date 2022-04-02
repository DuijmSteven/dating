<?php

namespace App\Middleware\User;

use App\RoleUser;
use App\User;
use Closure;
use Illuminate\Support\Str;

class VerifyAnonymousDomain
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
            Str::contains(request()->getHttpHost(), 'datevrij') &&
            (is_null(\Auth::user()) || \Auth::user()->roles()->get()[0]->id !== User::TYPE_ADMIN)
        ) {
            if (config('app.env') === 'production') {
                if (\Auth::user()) {
                    \Auth::logout();
                }

                return redirect('https://datevrij.nl/login');
            } elseif (config('app.env') === 'staging') {
                if (\Auth::user()) {
                    \Auth::logout();
                }

                return redirect('https://staging.datevrij.nl/login');
            }
        }

        return $next($request);
    }
}
