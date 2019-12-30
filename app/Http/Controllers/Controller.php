<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            view()->share('authenticatedUser', $this->authenticatedUser);

            return $next($request);
        });
    }
}
