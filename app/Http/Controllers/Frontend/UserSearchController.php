<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Managers\UserSearchManager;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use Cornford\Googlmapper\Models\Location;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequests\UserSearchRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mapper;

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
    private $userLocationService;

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
        try {
            $userSearchRequest->formatInput();
            $searchParameters = $userSearchRequest->all();

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

                $searchParameters['gender'] = \Auth::user()->meta->getLookingForGender();
            }

            // flash parameters to session so the next request can access them
            $userSearchRequest->session()->put('searchParameters', $searchParameters);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage() . $exception->getTraceAsString());

            toast()->message(trans('user_search.feedback.search_error'), 'error');

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

        $users = $this->userSearchManager->searchUsers(
            $searchParameters,
            true,
            $request->input('page')
        );

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => $this->buildTitleWith(trans('view_titles.search_results')),
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
        /** @var User $user */
        $user = \Auth::user();
        $lookingForGender = $user->meta->getLookingForGender();

        $searchParameters = [
            'city' => $user->meta->getCity(),
            'lat' => $user->meta->getLat(),
            'lng' => $user->meta->getLng(),
            'radius' => 40,
            'age' => null,
            'body_type' => null,
            'height' => null,
            'gender' => $lookingForGender,
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

            toast()->message(trans('user_search.feedback.search_error'), 'error');
        }

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now(),
            'title' => $this->buildTitleWith(trans('view_titles.home')),
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }
}
