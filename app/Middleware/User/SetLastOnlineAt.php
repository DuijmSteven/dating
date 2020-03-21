<?php

namespace App\Middleware\User;

use App\User;
use DB;
use Closure;

class SetLastOnlineAt
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }

        /** @var User $user */
        $user = auth()->user();

        if (null === $user->getLastOnlineAt() || $user->getLastOnlineAt()->diffInMinutes(now()) !== 5)
        {
            DB::table("users")
                ->where("id", $user->id)
                ->update(["last_online_at" => now()]);
        }
        return $next($request);
    }
}
