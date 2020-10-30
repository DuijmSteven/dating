<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
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
        $identityIsUsername = true;

        $this->validateLogin($request);

        $user = User
            ::where('username', $request->identity)
            ->first();

        if (! $user) {
            $identityIsUsername = false;
            $user = User::where('email', $request->identity)->first();
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json('The provided credentials are incorrect.', 401);
        }

        $userRoleId = $user->roles[0]->id;

        $relations = User::COMMON_RELATIONS;
        $relationCounts = [];

        if ($userRoleId === Role::ROLE_OPERATOR) {
            $relations = array_merge($relations, User::OPERATOR_RELATIONS);
            $relationCounts = array_merge($relationCounts, User::OPERATOR_RELATION_COUNTS);
        } elseif ($userRoleId === Role::ROLE_EDITOR) {
            $relations = array_merge($relations, User::EDITOR_RELATIONS);
            $relationCounts = array_merge($relationCounts, User::EDITOR_RELATION_COUNTS);
        }

        $user = User
            ::with($relations)
            ->withCount($relationCounts)
            ->where(
                $identityIsUsername ? 'username' : 'email',
                $request->identity
            )
            ->first();

        $plainTextToken = $user->createToken($request->identity)->plainTextToken;

        $response = [
            'token' => $plainTextToken,
            'role' => $user->roles[0]->id,
            'user' => $user
        ];

        return response()->json($response);
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
