<?php

namespace App\Http\Controllers\Frontend;

use App\EmailType;
use App\Http\Requests\Admin\Peasants\PeasantUpdateRequest;
use App\Managers\PeasantManager;
use App\Managers\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /** @var PeasantManager  */
    private $peasantManager;

    /**
     * UserController constructor.
     * @param User $user
     * @param UserManager $userManager
     */
    public function __construct(User $user, UserManager $userManager, PeasantManager $peasantManager)
    {
        parent::__construct();
        $this->user = $user;
        $this->userManager = $userManager;
        $this->peasantManager = $peasantManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param $userName
     * @return \Illuminate\Http\Response
     * @internal param $
     */
    public function show(string $username)
    {
        $user = User::where('username', $username)->first();

        if (!($user instanceof User)) {
            redirect(route('login'));
        }

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditProfile()
    {
        $viewData = [
            'user' => $this->authenticatedUser,
            'pageHeading' => 'Edit profile',
            'carbonNow' => Carbon::now(),
            'availableEmailTypes' => EmailType::all(),
            'userEmailTypeIds' => $this->authenticatedUser->emailTypes()->pluck('id')->toArray()
        ];

        $emailTypes = $this->authenticatedUser->emailTypes;

        return view(
            'frontend.users.edit-profile',
            array_merge(
                $viewData,
                [
                    'title' => 'Edit Profile - '. $this->authenticatedUser->username,
                    'carbonInstance' => new Carbon()
                ]
            )
        );
    }

    /**
     * @param PeasantUpdateRequest $peasantUpdateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PeasantUpdateRequest $peasantUpdateRequest)
    {
        $peasantUpdateRequest->formatInput();
        $peasantData = $peasantUpdateRequest->all();

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('userId'));

            toast()->message(trans('user_profile.feedback.profile_updated'), 'success');

        } catch (\Exception $exception) {
            \Log::error($exception);

            toast()->message(trans('user_profile.feedback.profile_not_updated'), 'error');
        }

        return redirect()->back();
    }

    public function setLocale(string $locale)
    {
        $user = Auth::user();
        $user->setLocale($locale);
        $user->save();
        
        return redirect()->back();
    }
}
