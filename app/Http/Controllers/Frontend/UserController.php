<?php

namespace App\Http\Controllers\Frontend;

use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers\Frontend
 */
class UserController extends FrontendController
{
    /** @var User */
    private $user;

    /** @var UserManager  */
    private $userManager;

    /**
     * UserController constructor.
     * @param User $user
     * @param UserManager $userManager
     */
    public function __construct(User $user, UserManager $userManager)
    {
        parent::__construct();
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
        /*dd($this->user->with(['meta', 'roles'])->whereHas('roles', function ($query) {
            $query->where('name', 'peasant');
            $query->orWhere('name', 'bot');
        })->paginate(15));*/


        $viewData = [
            'users' => $this->user->with(['meta', 'roles'])->whereHas('roles', function ($query) {
                $query->where('name', 'peasant');
                $query->orWhere('name', 'bot');
            })->paginate(15),
            'carbonNow' => Carbon::now()
        ];

        return view(
            'frontend.users.overview',
            array_merge(
                $viewData,
                [
                    'title' => 'Profiles'
                ]
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $userId
     * @return \Illuminate\Http\Response
     * @internal param $
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);

        $viewData = [
            'user' => $user,
            'carbonNow' => Carbon::now()
        ];

        return view(
            'frontend.users.profile',
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
            'frontend.users.online',
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
