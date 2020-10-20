<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserActivityService;
use App\Traits\Users\AuthenticatesUsers;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sanctumToken(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('email', $request->identity)->first();

        if (! $user) {
            $user = User::where('username', $request->identity)->first();
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json('The provided credentials are incorrect.', 401);
        }

        $plainTextToken = $user->createToken($request->identity)->plainTextToken;

        Log::info($plainTextToken);

        return response()->json($plainTextToken);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->middleware('guest', ['except' => 'logout']);
    }
}
