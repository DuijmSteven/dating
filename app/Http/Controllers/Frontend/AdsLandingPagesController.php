<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\Services\RegistrationService;
use App\User;
use Illuminate\Http\Request;

class AdsLandingPagesController extends FrontendController
{
    private RegistrationService $registrationService;

    /**
     * LandingPageController constructor.
     * @param RegistrationService $registrationService
     */
    public function __construct(
        RegistrationService $registrationService,
        App\Services\UserActivityService $userActivityService
    ) {
        $this->registrationService = $registrationService;
        parent::__construct($userActivityService);
    }

    public function showLP(Request $request, $id, $lpType = 'register')
    {
        if(view()->exists('frontend.landing-pages.ads.' . config('app.directory_name') . '.' . $id)) {

            $viewData = [];

            $viewData = $this->registrationService->checkAffiliateRequestDataAndSetRegistrationViewData($request, $viewData);

            if ($lpType === 'login') {
                $viewData['lpType'] = 'login';
            } else {
                $viewData['lpType'] = 'register';
            }

            $viewData['users'] = $this->getUsers();
            $viewData['totalUsersCount'] = User::count();
            $viewData['id'] = $id;

            return view(
                'frontend.landing-pages.ads.' . str_replace('.', '-', config('app.name')) . '.' . $id,
                $viewData
            );
        } else {
            return abort(404);
        }
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    private function getUsers()
    {
        $countryCode = 'nl';

        $users = User::with(['roles', 'meta'])->whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereHas('meta', function ($query) use ($countryCode) {
                $query->where('gender', User::GENDER_FEMALE);
                $query->where('country', $countryCode);
            })
            ->whereDoesntHave('meta', function ($query) {
                $query->where('too_slutty_for_ads', true);
            })
            ->whereHas('profileImage')
            ->inRandomOrder()
            ->take(12)
            ->get();

        return $users;
    }
}
