<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\Services\RegistrationService;
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

            $viewData['id'] = $id;

            return view(
                'frontend.landing-pages.ads.' . str_replace('.', '-', config('app.name')) . '.' . $id,
                $viewData
            );
        } else {
            return abort(404);
        }
    }
}
