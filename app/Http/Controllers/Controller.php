<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use App\Services\UserActivityService;
use App\User;
use Carbon\Carbon;
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
    protected $userActivityService;

    /**
     * Controller constructor.
     */
    public function __construct(
        UserActivityService $userActivityService
    ) {
        $this->userActivityService = $userActivityService;

        $this->middleware(function ($request, $next) use ($userActivityService) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            $onlineIds = $userActivityService->getOnlineUserIds(
                $userActivityService::GENERAL_ONLINE_TIMEFRAME_IN_MINUTES
            );

            $this->onlineUserIds = $onlineIds;

            view()->share('authenticatedUser', $this->authenticatedUser);
            view()->share('onlineUserIds', $this->onlineUserIds);
            //view()->share('carbonNow', Carbon::now());

            return $next($request);
        });
    }
}
