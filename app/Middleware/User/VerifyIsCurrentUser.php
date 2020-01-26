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
        $userIdRouteParameter = $request->route('userId');

        if (
            $usernameRouteParameter && \Auth::user()->getUserName() === $usernameRouteParameter ||
            $userIdRouteParameter && \Auth::user()->getId() === (int) $userIdRouteParameter
        ) {
            return $next($request);
        }

        return redirect()->route('home');
    }
}
