<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AdsLandingPagesController extends FrontendController
{
    private RegistrationService $registrationService;

    /**
     * LandingPageController constructor.
     * @param RegistrationService $registrationService
     */
    public function __construct(
        App\Services\RegistrationService $registrationService,
        App\Services\OnlineUsersService $onlineUsersService
    ) {
        $this->registrationService = $registrationService;
        parent::__construct($onlineUsersService);
    }

    public function showLP(Request $request, $id)
    {
        if(view()->exists('frontend.landing-pages.ads.' . str_replace('.', '-', config('app.name')) . '.' . $id)) {

            $viewData = [];

            $viewData = $this->registrationService->checkAffiliateRequestDataAndSetRegistrationViewData($request, $viewData);

            return view(
                'frontend.landing-pages.ads.' . str_replace('.', '-', config('app.name')) . '.' . $id,
                $viewData
            );
        } else {
            return abort(404);
        }
    }
}
