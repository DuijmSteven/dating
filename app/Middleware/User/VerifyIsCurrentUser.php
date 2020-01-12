<?php

namespace App\Middleware\User;

use Closure;

class VerifyIsCurrentUser
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
        $usernameRouteParameter = $request->route('username');

        if (
            is_null($usernameRouteParameter) ||
            \Auth::user()->getUserName() !== $usernameRouteParameter
        ) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
