<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Managers\AffiliateManager;
use App\Services\UserLocationService;
use App\User;
use App\UserFingerprint;
use App\UserIp;
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

//        $fingerprint = $request->get('user_fingerprint');
//
//        if ($fingerprint) {
//            $existingFingerprints = UserFingerprint::all()->pluck('fingerprint')->toArray();
//
//            if (!in_array($fingerprint, $existingFingerprints)) {
//                $userFingerprintInstance = new \App\UserFingerprint();
//                $userFingerprintInstance->setUserId($user->id);
//                $userFingerprintInstance->setFingerprint($fingerprint);
//                $userFingerprintInstance->save();
//            }
//        } else {
//            \Log::debug('No fingerprint on login of user with ID: ' . $user->id);
//        }
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
