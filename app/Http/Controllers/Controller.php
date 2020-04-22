<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Kim\Activity\Activity;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var User|null */
    protected $authenticatedUser;
    protected $onlineUserIds;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            $onlineIds = Activity::users(10)->pluck('user_id')->toArray();

            $this->onlineUserIds = $onlineIds;

            view()->share('authenticatedUser', $this->authenticatedUser);
            view()->share('onlineUserIds', $this->onlineUserIds);

            return $next($request);
        });
    }
}
