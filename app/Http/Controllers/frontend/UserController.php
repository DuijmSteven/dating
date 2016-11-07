<?php

namespace App\Http\Controllers\frontend;

use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /** @var User */
    private $user;

    /** @var UserManager  */
    private $userManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, UserManager $userManager)
    {
        $this->user = $user;
        $this->userManager = $userManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = [
            'users' => $this->user->with(['meta', 'roles'])->paginate(15),
            'carbonNow' => Carbon::now()
        ];

        return view(
            'frontend/users/index',
            array_merge(
                $viewData,
                [
                    'title' => 'Profiles',
                    'hasSidebar' => true
                ]
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);

        $viewData = [
            'user' => $user,
            'carbonNow' => Carbon::now()
        ];

        return view(
            'frontend/users/profile',
            array_merge(
                $viewData,
                [
                    'title' => 'Profile - '. $user->username
                ]
            )
        );
    }

    /**
     * Online users view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function online(Request $request)
    {
        $viewData = [
            'users' => $this->userManager->latestOnline(15),
            'carbonNow' => Carbon::now()
        ];
        return view(
            'frontend/users/online',
            array_merge(
                $viewData,
                [
                    'title' => 'Online users',
                    'hasSidebar' => true
                ]
            )
        );
    }
}
