<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Managers\UserSearchManager;
use App\Services\LocationService;
use App\Services\UserLocationService;
use App\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequests\UserSearchRequest;

/**
 * Class UserSearchController
 * @package App\Http\Controllers\Frontend
 */
class UserSearchController extends FrontendController
{
    private $userSearchManager;

    /**
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;

    /**
     * UserSearchController constructor.
     * @param UserSearchManager $userSearchManager
     */
    public function __construct(
        UserSearchManager $userSearchManager,
        UserLocationService $userLocationService
    ) {
        $this->userSearchManager = $userSearchManager;
        parent::__construct();
        $this->userLocationService = $userLocationService;
    }

    /**
     * Search users view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearch()
    {
        $viewData = [
        ];
        return view(
            'frontend.users.search.overview',
            $viewData
        );
    }

    /**
     * Accepts POST requests, formats input fields, flashes searchParameters
     * to the session and redirects to the 'users.search.results.get' route
     *
     * @param UserSearchRequest $userSearchRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(UserSearchRequest $userSearchRequest)
    {
        $userSearchRequest->formatInput();
        $searchParameters = $userSearchRequest->all();

        if (isset($searchParameters['age'])) {

            $ageMax = 100;

            $largestAgeLimit = (int) array_keys(UserConstants::$ageGroups)[sizeof(UserConstants::$ageGroups) - 1];

            if ($searchParameters['age'] != $largestAgeLimit) {
                [$ageMin, $ageMax] = explode('-', $searchParameters['age']);
            } else {
                $ageMin = $largestAgeLimit;
            }

            $date = new \DateTime;
            // The "Min" and "Max" are reversed on purpose in their usages, since the resulting date
            // from the minimum age would be more recent than the one resulting from the maximum age
            $formattedMaxDate = $date->modify('-' . $ageMin . ' years')->format('Y-m-d H:i:s');

            $date = new \DateTime;
            $formattedMinDate = $date->modify('-' . $ageMax . ' years')->format('Y-m-d H:i:s');

            $searchParameters['dob'] = [];
            $searchParameters['dob']['min'] = $formattedMinDate;
            $searchParameters['dob']['max'] = $formattedMaxDate;
        }

        // flash parameters to session so the next request can access them
        $userSearchRequest->session()->put('searchParameters', $searchParameters);

        // redirect to search results' first page
        return redirect()->route('users.search.results.get', ['page' => 1]);
    }

    /**
     * Uses flashed searchParameters from session to search for use   rs and
     * returns the search-results view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearchResults(Request $request)
    {
        // get searchParameters from session
        $searchParameters = $request->session()->get('searchParameters');

        $users = $this->userSearchManager->searchUsers(
            $searchParameters,
            true,
            $request->input('page')
        );

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => 'Search results - ' . config('app.name'),
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }

    public function showInitialSearchResults(Request $request)
    {
        $userCityName = $this->userLocationService->getUserCityName();

        //dd($userCityName);

//        if (!$userCity) {
//            $userCity = 'Amsterdam';
//        }

        // get searchParameters from session
//        $searchParameters = [
//            'city' => $userCity
//            'lat' =>
//            'lng' =>
//            'radius' => 20
//        ];

        $users = $this->userSearchManager->searchUsers(
            $searchParameters,
            true,
            $request->input('page')
        );

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => 'Search results - ' . config('app.name'),
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }
}
