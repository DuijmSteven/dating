<?php

namespace App\Http\Controllers\Frontend;

use App\EmailType;
use App\Http\Requests\Admin\Peasants\PeasantUpdateRequest;
use App\Mail\Deactivated;
use App\Managers\PeasantManager;
use App\Managers\UserManager;
use App\Milestone;
use App\User;
use App\UserView;
use App\UserViews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $viewData = [
            'users' => $this->user->with(['meta', 'roles'])->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_PEASANT);
                $query->orWhere('id', User::TYPE_BOT);
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

    public function acceptProfileCompletionMessage()
    {
        Auth::user()->milestones()->attach(Milestone::ACCEPTED_PROFILE_COMPLETION_MESSAGE);
        return redirect()->back();
    }

    /**
     * @param string $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $username)
    {
        $user = User::with('emailTypeInstances', 'emailTypes', 'roles')->where('username', $username)->first();

        if (!($user instanceof User)) {
            redirect(route('home'));
        }

        if ($this->authenticatedUser->isPeasant()) {
            if (
                $user->isPeasant() &&
                $user->isMailable
            ) {
                $timeNow = Carbon::now('Europe/Amsterdam'); // Current time

                $hasRecentProfileViewedEmails = $user
                    ->emailTypeInstances()->where('id', EmailType::PROFILE_VIEWED)
                    ->where('created_at', '>=', Carbon::today('Europe/Amsterdam')
                        ->setTime($timeNow->hour - 2, 00, 00)
                        ->toDateTimeString()
                    )
                    ->get();

                if (!count($hasRecentProfileViewedEmails)) {
                    $this->userManager->setProfileViewedEmail(
                        $user,
                        $this->authenticatedUser
                    );
                }
            }

            $this->userManager->storeProfileView(
                $this->authenticatedUser,
                $user,
                UserView::TYPE_PEASANT
            );
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
                    'title' => $this->buildTitleWith(trans('view_titles.single_profile') . ' - '. $user->username)
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
            'availableEmailTypes' => EmailType::where('editable', true)->get(),
            'userEmailTypeIds' => $this->authenticatedUser->emailTypes()
                ->where('editable', 1)
                ->pluck('id')
                ->toArray()
        ];

        $emailTypes = $this->authenticatedUser->emailTypes;

        return view(
            'frontend.users.edit-profile',
            array_merge(
                $viewData,
                [
                    'title' => $this->buildTitleWith(
                        trans('view_titles.edit_profile') . ' - '. $this->authenticatedUser->username
                    ),
                    'carbonInstance' => new Carbon()
                ]
            )
        );
    }

    public function redirectBack(Request $request)
    {
        return redirect()->back();
    }

    /**
     * @param PeasantUpdateRequest $peasantUpdateRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PeasantUpdateRequest $peasantUpdateRequest)
    {
        $peasantUpdateRequest->formatInput();
        $peasantData = $peasantUpdateRequest->all();

        if (isset($peasantData['user_images']) || isset($peasantData['profile_image'])) {
            /** @var User $peasant */
            $peasant = User::find($peasantUpdateRequest->route('userId'));

            $peasantImagesCount = $peasant->images->count();

            $uploadedImagesCount = 0;

            if (isset($peasantData['user_images'])) {
                $uploadedImagesCount += count($peasantData['user_images']);
            }

            if (isset($peasantData['profile_image'])) {
                $uploadedImagesCount += 1;
            }

            if ($peasantImagesCount + $uploadedImagesCount > 10) {
                toastr()->error(trans('edit_profile.image_limit_reached'));

                return redirect()->back();
            }
        }

        try {
            $this->peasantManager->updatePeasant($peasantData, $peasantUpdateRequest->route('userId'));
            toastr()->success(trans('user_profile.feedback.profile_updated'));
        } catch (\Exception $exception) {
            \Log::error($exception);
            toastr()->error(trans('user_profile.feedback.profile_not_updated'));
        }

        return redirect()->back();
    }

    /**
     * @param int $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(Request $request)
    {
        try {
            $user = $this->authenticatedUser;
            $user->setActive(false);
            $user->setDeactivatedAt(new Carbon('now'));
            $user->save();

            $deactivatedEmail = (new Deactivated($user))->onQueue('emails');

            Mail::to($user)
                ->queue($deactivatedEmail);
        } catch (\Exception $exception) {
            \Log::error($exception);
        }

        Auth::guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('users.deactivated.get');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDeactivated()
    {
        return view(
            'frontend.users.deactivated',
            [
                'title' => $this->buildTitleWith(trans('view_titles.deactivated'))
            ]
        );
    }

    public function setLocale(string $locale)
    {
        $user = Auth::user();
        $user->setLocale($locale);
        $user->save();
        
        return redirect()->back();
    }
}
