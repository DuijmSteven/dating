<?php

namespace App\Http\Controllers\Frontend;

use App\Managers\UserSearchManager;
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
     * UserSearchController constructor.
     * @param UserSearchManager $userSearchManager
     */
    public function __construct(UserSearchManager $userSearchManager)
    {
        $this->userSearchManager = $userSearchManager;
        parent::__construct();
    }

    /**
     * Accepts POST requests, formats input fields, flashes searchParameters
     * to the session and redirects to the 'users.search.results.get' route
     *
     * @param UserSearchRequest $userSearchRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSearch(UserSearchRequest $userSearchRequest)
    {
        $userSearchRequest->formatInput();
        $searchParameters = $userSearchRequest->all();

        if (isset($searchParameters['age'])) {
            [$ageMin, $ageMax] = explode('-', $searchParameters['age']);

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
        $userSearchRequest->session()->flash('searchParameters', $searchParameters);

        // redirect to search results' first page
        return redirect()->route('users.search.results.get', ['page' => 1]);
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
            array_merge(
                $viewData,
                [
                    'hasSidebar' => true
                ]
            )
        );
    }

    /**
     * Uses flashed searchParameters from session to search for users and
     * returns the search-results view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearchResults(Request $request)
    {
        // get searchParameters from session
        $searchParameters = session('searchParameters');

        // flash searchParameters to session so the next request can access them
        $request->session()->flash('searchParameters', $searchParameters);
        $users = $this->userSearchManager->searchUsers($searchParameters, true, $request->input('page'));

        $viewData = [
            'users' => $users,
            'carbonNow' => Carbon::now()
        ];
        return view(
            'frontend.users.search.results',
            array_merge(
                $viewData,
                [
                    'hasSidebar' => true
                ]
            )
        );
    }
}
