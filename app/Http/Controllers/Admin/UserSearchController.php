<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UserSearchRequest;
use App\Managers\UserSearchManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UserSearchController extends Controller
{
    /** @var UserSearchManager */
    private $userSearchManager;

    /**
     * UserSearchController constructor.
     * @param UserSearchManager $userSearchManager
     */
    public function __construct(
        UserSearchManager $userSearchManager
    ) {
        $this->userSearchManager = $userSearchManager;
        parent::__construct();
    }

    public function search(UserSearchRequest $userSearchRequest)
    {
        try {
            $userSearchRequest->formatInput();
            $searchParameters = $userSearchRequest->all();

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

            if (isset($searchParameters['created_at_after'])) {
                $searchParameters['created_at_after'] = (new Carbon($searchParameters['created_at_after']))
                    ->tz('Europe/Amsterdam')
                    ->format('Y-m-d H:i:s');
            }

            if (isset($searchParameters['created_at_before'])) {
                $searchParameters['created_at_before'] = (new Carbon($searchParameters['created_at_before']))
                    ->addDays(1)
                    ->tz('Europe/Amsterdam')
                    ->format('Y-m-d H:i:s');
            }

            // flash parameters to session so the next request can access them
            $userSearchRequest->session()->put('searchParameters', $searchParameters);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage() . $exception->getTraceAsString());

            toastr()->error(trans('user_search.feedback.search_error'));

            return redirect()->back();
        }

        // redirect to search results' first page
        return redirect()->route('admin.users.search.results.get', ['page' => 1]);
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
            'carbonNow' => Carbon::now(),
            'title' => 'Search results',
            'editBotRoute' =>'admin.bots.edit.get'
        ];

        $routeName = '';
        $roleId = (int) $searchParameters['role_id'];
        if ($roleId === User::TYPE_PEASANT) {
            $routeName = 'admin.peasants.overview';
            $viewData['peasants'] = $users;
        } elseif ($roleId === User::TYPE_BOT) {
            $routeName = 'admin.bots.overview';
            $viewData['bots'] = $users;
        } elseif ($roleId === User::TYPE_OPERATOR) {
            $routeName = 'admin.operators.overview';
            $viewData['operators'] = $users;
        } else {
            $routeName = 'admin.editors.overview';
            $viewData['editors'] = $users;
        }

        return view(
            $routeName,
            $viewData
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showInitialSearchResults(Request $request)
    {
        $user = $this->authenticatedUser;

        if (!$user->getActive()) {
            $user->setActive(true);
            $user->save();
        }

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
            'title' => 'Search results',
            'city' => $city,
            'radius' => 50
        ];

        return view(
            'frontend.users.search.results',
            $viewData
        );
    }
}
