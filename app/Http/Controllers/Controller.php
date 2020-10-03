<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\Services\OnlineUsersService;
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
    protected $onlineUserIds;
    /**
     * @var OnlineUsersService
     */
    private OnlineUsersService $onlineUsersService;

    /**
     * Controller constructor.
     */
    public function __construct(
        OnlineUsersService $onlineUsersService
    ) {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            $onlineIds = $this->onlineUsersService->getOnlineUserIds();

            $this->onlineUserIds = $onlineIds;

            view()->share('authenticatedUser', $this->authenticatedUser);
            view()->share('onlineUserIds', $this->onlineUserIds);

            return $next($request);
        });
        $this->onlineUsersService = $onlineUsersService;
    }
}
