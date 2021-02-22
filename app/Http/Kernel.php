<?php

namespace App\Http;

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\VerifyCsrfToken;
use App\Middleware\Api\CanManipulateUser;
use App\Middleware\User\HasNonCompletedPayment;
use App\Middleware\User\SetLastOnlineAt;
use App\Middleware\User\SetLocale;
use App\Middleware\User\VerifyAdmin;
use App\Middleware\User\VerifyAnonymousDomain;
use App\Middleware\User\VerifyEditor;
use App\Middleware\User\VerifyIsCurrentUser;
use App\Middleware\User\VerifyNotAnonymousDomain;
use App\Middleware\User\VerifyNotEditor;
use App\Middleware\User\VerifyNotOperator;
use App\Middleware\User\VerifyOperator;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
        CheckForMaintenanceMode::class,
        HandleCors::class,
        //ConvertEmptyStringsToNull::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
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
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'throttle' => ThrottleRequests::class,
        'admin' => VerifyAdmin::class,
        'editor' => VerifyEditor::class,
        'not_editor' => VerifyNotEditor::class,
        'not_operator' => VerifyNotOperator::class,
        'current_user' => VerifyIsCurrentUser::class,
        'operator' => VerifyOperator::class,
        'anonymous_domain' => VerifyAnonymousDomain::class,
        'not_anonymous_domain' => VerifyNotAnonymousDomain::class,
        'signed' => ValidateSignature::class,
        'api_admin' => \App\Middleware\Api\VerifyAdmin::class,
        'api_operator' => \App\Middleware\Api\VerifyOperator::class,
        'api_editor' => \App\Middleware\Api\VerifyEditor::class,
        'can_manipulate_user' => CanManipulateUser::class,
        'has_non_completed_payment' => HasNonCompletedPayment::class
    ];
}
