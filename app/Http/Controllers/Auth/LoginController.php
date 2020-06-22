<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Managers\AffiliateManager;
use App\Services\UserLocationService;
use App\User;
use Illuminate\Http\Request;
use App\Traits\Users\AuthenticatesUsers;

/**
 * Class LoginController
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|void
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->getActive()) {
            $user->setActive(true);
            $user->setDeactivatedAt(null);
            $user->save();
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isEditor()) {
            return redirect()->route('editors.bots.created.overview');
        }

        if ($user->isOperator()) {
            return redirect()->route('operator-platform.dashboard');
        }

        if ($request->ip()) {
            $userIpInstance = new \App\UserIp();
            $userIpInstance->setUserId($user->id);
            $userIpInstance->setIp($request->ip());
            $userIpInstance->save();
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('guest', ['except' => 'logout']);
    }
}
