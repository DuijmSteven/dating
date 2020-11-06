<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\UserRequests\UserSearchRequest;
use App\Managers\UserSearchManager;
use App\Services\OnlineUsersService;
use App\Services\UserActivityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

/**
 * Class UserSearchController
 * @package App\Http\Controllers\Frontend
 */
class UserSearchController extends FrontendController
{
    /** @var UserSearchManager */
    private $userSearchManager;
    /**
     * @var OnlineUsersService
     */
    private OnlineUsersService $onlineUsersService;

    /**
     * UserSearchController constructor.
     * @param UserSearchManager $userSearchManager
     */
    public function __construct(
        UserSearchManager $userSearchManager,
        UserActivityService $userActivityService
    ) {
        $this->userSearchManager = $userSearchManager;
        parent::__construct($userActivityService);
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
        try {
            $searchParameters = $userSearchRequest->all();
            $searchParameters['gender'] = $this->authenticatedUser->meta->getLookingForGender();
            $searchParameters['looking_for_gender'] = $this->authenticatedUser->meta->getGender();

            if (isset($searchParameters['with_profile_image'])) {
                Cookie::queue('searchWithProfileImageSet', $searchParameters['with_profile_image'], 60);
            }

            if (isset($searchParameters['age'])) {
                $ageMax = 100;

                $largestAgeLimit = (int)array_keys(UserConstants::$ageGroups)[sizeof(UserConstants::$ageGroups) - 1];

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
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage() . $exception->getTraceAsString());

            toastr()->error(trans('user_search.feedback.search_error'));

            return redirect()->back();
        }

        // redirect to search results' first page
        return redirect()->route('users.search.results.get', ['page' => 1]);
    }

    /**
     * Uses flashed searchParameters from session to search for use   rs and
     * returns the search-results view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function getSearchResults(Request $request)
    {
        // get searchParameters from session
        $searchParameters = $request->session()->get('searchParameters');
        $searchParameters['active'] = true;
        $searchParameters['radius'] = 80;

        if (!isset($searchParameters['city_name'])) {
            $searchParameters['city_name'] = 'Amsterdam';
        }

        $users = $this->userSearchManager->searchUsers(
            $searchParameters,
            true,
            $request->input('page'),
            [
                'type' => UserSearchManager::ORDERING_TYPE_RADIUS
            ]
        );

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => $this->buildTitleWith(trans('view_titles.search_results')),
            'city' => $searchParameters['city_name'],
            'radius' => 80
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showInitialSearchResults(Request $request)
    {
        if (!$this->authenticatedUser->meta->getCity()) {
            $city = 'Amsterdam';
            $lat = 52.379189;
            $lng = 4.899431;
        } else {
            $city = $this->authenticatedUser->meta->getCity();
            $lat = $this->authenticatedUser->meta->getLat();
            $lng = $this->authenticatedUser->meta->getLng();
        }

        if (Cookie::get('searchWithProfileImageSet')) {
            $withProfileImage = Cookie::get('searchWithProfileImageSet');
        } else {
            if ($this->authenticatedUser->meta->looking_for_gender === 2) {
                $withProfileImage = false;
            } else {
                $withProfileImage = true;
            }
        }

        $radius = 80;

        $searchParameters = [
            'city_name' => $city,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius,
            'age' => null,
            'body_type' => null,
            'active' => 1,
            'height' => null,
            'with_profile_image' => $withProfileImage,
            'gender' => $this->authenticatedUser->meta->getLookingForGender(),
            'looking_for_gender' => $this->authenticatedUser->meta->getGender(),
        ];

        $request->session()->put('searchParameters', $searchParameters);

        $randomizationKey = $request->session()->get('initial_search_randomization_key');

        if (!$randomizationKey) {
            $randomizationKey = (string) rand(1, 999);
            $request->session()->put('initial_search_randomization_key', $randomizationKey);
        }

        try {
            $users = $this->userSearchManager->searchUsers(
                $searchParameters,
                true,
                $request->input('page'),
                [
                    'type' => UserSearchManager::ORDERING_TYPE_RANDOMIZED,
                    'randomization_key' => $randomizationKey
                ]
            );
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage() . $exception->getTraceAsString());
            toastr()->error(trans('user_search.feedback.search_error'));
        }

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => $this->buildTitleWith(trans('view_titles.home')),
            'city' => $city,
            'radius' => 50
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }
}
