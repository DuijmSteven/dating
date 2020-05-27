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
            if (config('app.env') === 'production') {
                if (is_null(\Auth::user())) {
                    return redirect('https://devely-operators.nl/operators/login');
                } else if (\Auth::user()->roles()->get()[0]->id === User::TYPE_EDITOR) {
                    return redirect('https://devely-operators.nl/editor/bots/created');
                } else if (\Auth::user()->roles()->get()[0]->id === User::TYPE_OPERATOR) {
                    return redirect('https://devely-operators.nl/operator-platform/dashboard');
                } else {
                    \Auth::logout();
                    return redirect('https://devely-operators.nl');
                }
            } elseif (config('app.env') === 'staging') {
                if (is_null(\Auth::user())) {
                    return redirect('https://staging.devely-operators.nl/operators/login');
                } else if (\Auth::user()->roles()->get()[0]->id === User::TYPE_EDITOR) {
                    return redirect('https://staging.devely-operators.nl/editor/bots/created');
                } else if (\Auth::user()->roles()->get()[0]->id === User::TYPE_OPERATOR) {
                    return redirect('https://staging.devely-operators.nl/operator-platform/dashboard');
                } else {
                    \Auth::logout();
                    return redirect('https://staging.devely-operators.nl');
                }
            }
        }

        return $next($request);
    }
}
