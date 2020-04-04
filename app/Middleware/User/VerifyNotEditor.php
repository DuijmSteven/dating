<?php

namespace App\Middleware\User;

use App\RoleUser;
use Closure;

class VerifyNotEditor
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
        if (is_null(\Auth::user())) {
            return redirect()->route('landing-page.show-login');
        }

        if (
            (\Auth::user()->roles()->get()[0]->name === 'editor')
        ) {
            return redirect()->route(
                'admin.editors.created-bots.overview',
                [
                    'editorId' => \Auth::user()->getId()
                ]
            );
        }

        return $next($request);
    }
}
