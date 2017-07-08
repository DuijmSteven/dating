<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\User;
use DebugBar\DebugBar;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var User|null */
    protected $authenticatedUser;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        if (app()->environment() !== 'production') {
            DebugBar::enable();
        }

        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            view()->share('authenticatedUser', $this->authenticatedUser);

            return $next($request);
        });
    }
}
