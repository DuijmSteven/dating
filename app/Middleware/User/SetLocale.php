<?php

namespace App\Middleware\User;

use App\RoleUser;
use Carbon\Carbon;
use Closure;

class SetLocale
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
        $locale = config('app.locale');

        if ($request->user() && $request->user()->getLocale()) {
            $locale = $request->user()->getLocale();
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
