<?php

namespace App\Http\Controllers;

use App\Managers\UserManager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $authenticatedUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->authenticatedUser = UserManager::getAndFormatAuthenticatedUser();

            view()->share('authenticatedUser', $this->authenticatedUser);

            return $next($request);
        });

    }
}
