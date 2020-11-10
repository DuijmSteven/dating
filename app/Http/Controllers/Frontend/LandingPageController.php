<?php

namespace App\Http\Controllers\Frontend;

use App;
use App\Services\RegistrationService;
use App\Services\UserActivityService;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingPageController extends FrontendController
{
    private RegistrationService $registrationService;
    private UserLocationService $userLocationService;

    /**
     * LandingPageController constructor.
     * @param RegistrationService $registrationService
     */
    public function __construct(
        RegistrationService $registrationService,
        UserLocationService $userLocationService,
        UserActivityService $userActivityService
    ) {
        $this->registrationService = $registrationService;
        parent::__construct($userActivityService);
        $this->userLocationService = $userLocationService;
    }

    public
    function showRegister(Request $request)
    {
        $this->setLocale($request);
        $users = $this->getUsers();
        $testimonials = $this->getTestimonials();

        $viewData = [
            'title' => 'De beste datingsite voor sex dating | ' . ucfirst(\config('app.name')),
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'testimonials' => $testimonials,
            'formType' => 'register',
            'canonical' => 'https://' . config('app.name') . '/'
        ];

        $viewData = $this->registrationService->checkAffiliateRequestDataAndSetRegistrationViewData($request, $viewData);

        return view(
            'frontend.landing-pages.' . str_replace('.', '-', config('app.name')) . '.1',
            $viewData
        );
    }

    public
    function showLogin(Request $request)
    {
        $this->setLocale($request);
        $users = $this->getUsers();
        $testimonials = $this->getTestimonials();

        return view(
            'frontend.landing-pages.' . str_replace('.', '-', config('app.name')) . '.1',
            [
                'title' => 'De beste datingsite voor sex dating | ' . ucfirst(\config('app.name')),
                'users' => $users,
                'carbonNow' => Carbon::now(),
                'testimonials' => $testimonials,
                'formType' => 'login',
                'canonical' => 'https://' . config('app.name') . '/'
            ]
        );
    }

    /**
     * @return array
     */
    private
    function getTestimonials(): array
    {
        $testimonials = [
            [
                'quote' => 'Ik weet niet of ik zonder deze website ook zo snel contact gehad zou hebben. 
                            Erg makkelijk in gebruik, leuke contacten en na een paar weken had ik de eerste date! Super bedankt.',
                'imgSource' => asset('img/lp/testimonials/timo.jpg'),
                'names' => 'Timo, 28'
            ],
            [
                'quote' => 'Als ‘mooie’ vrouw krijg je vaak eindeloos veel berichten en vriendschapsverzoeken, maar gelukkig dat ik hier wel mensen trof die serieus waren.',
                'imgSource' => asset('img/lp/testimonials/amber.jpg'),
                'names' => 'Amber, 24'
            ],
            [
                'quote' => 'Was op zoek naar een leuke date en gevonden... Super geslaagd dus',
                'imgSource' => asset('img/lp/testimonials/remco.jpg'),
                'names' => 'Remco, 36'
            ],
            [
                'quote' => 'Na een spanningsloos huwelijk was ik wel toe aan wat leven in de brouwerij. Hier heb ik een paar leuke vrouwen leren kennen waar ik zo nu en dan eens een bakkie koffie mee kan drinken.',
                'imgSource' => asset('img/lp/testimonials/nico.jpg'),
                'names' => 'Nico, 36'
            ],
            [
                'quote' => 'Zelf had ik niet helemaal meer verwacht van zo’n datingsite gebruik te maken. Ik verbaasde me in het beginsel over het gemak waar je een gesprek mee voert en dat het gaandeweg echt leuker wordt. Uiteindelijk heb ik nog een paar weken gechat, maar nu ben ik gelukkig en hopelijk voorlopig voorzien haha',
                'imgSource' => asset('img/lp/testimonials/johan.jpg'),
                'names' => 'Johan, 42'
            ],
            [
                'quote' => 'Was wel toe aan iets anders dan Tinder en gewoon oprecht een leuk gesprekje voeren voor je elkaar in het echt treft en de hemd van het lijf scheurt.',
                'imgSource' => asset('img/lp/testimonials/laura.jpg'),
                'names' => 'Laura, 29'
            ],
            [
                'quote' => 'Op mijn leeftijd sluit je niet meer zo makkelijk aan in de kroeg. Op enkele datingsites kwam ik bedrogen uit, maar ik ben blij dat ik op altijdsex toch nog invulling heb kunnen geven aan mijn lusten, ook al ben ik niet meer de jongste, de oudste zal ik ook zeker niet zijn, toch?',
                'imgSource' => asset('img/lp/testimonials/mrjones.jpg'),
                'names' => 'Mr. Jones, 68'
            ],
        ];
        return $testimonials;
    }

    /**
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    private
    function getUsers()
    {
//        $countryCode = strtolower($this->userLocationService->getCountryCodeFromIp($this->userLocationService->getUserIp()));
//
//        if (!in_array($countryCode, ['nl', 'be'])) {
        $countryCode = 'nl';
//        }

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

    /**
     * @param Request $request
     */
    private
    function setLocale(Request $request): void
    {
        $locale = 'nl';

        $localeFromRequest = $request->get('locale');

        if ($localeFromRequest && in_array($localeFromRequest, ['nl', 'en'])) {
            $locale = $request->get('locale');
        }

        app()->setLocale($locale);
    }
}
