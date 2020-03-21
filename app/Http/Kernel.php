<?php

namespace App\Http;

use App\Middleware\User\SetLastOnlineAt;
use App\Middleware\User\SetLocale;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            SetLocale::class,
            SetLastOnlineAt::class
        ],

        'api' => [
/*            'throttle:60,1',*/
            'bindings',
            SetLastOnlineAt::class
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'admin' => \App\Middleware\User\VerifyAdmin::class,
        'editor' => \App\Middleware\User\VerifyEditor::class,
        'not_editor' => \App\Middleware\User\VerifyNotEditor::class,
        'not_operator' => \App\Middleware\User\VerifyNotOperator::class,
        'current_user' => \App\Middleware\User\VerifyIsCurrentUser::class,
        'operator' => \App\Middleware\User\VerifyOperator::class,
    ];
}
