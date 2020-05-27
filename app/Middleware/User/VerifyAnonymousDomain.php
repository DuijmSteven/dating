<?php

namespace App\Middleware\User;

use App\RoleUser;
use App\User;
use Closure;

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
            \Auth::user()->roles()->get()[0]->id !== User::TYPE_ADMIN &&
            request()->getHttpHost() === 'dating.test'
        ) {
            dd('not an admin or domain not anonymous');
            return redirect('https://dating.test/');
        }

        return $next($request);
    }
}
