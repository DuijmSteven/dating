<?php

namespace App\Http\Controllers\Frontend;

use App\Services\OnlineUsersService;
use App\Services\UserActivityService;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Frontend
 */
class DashboardController extends FrontendController
{
    /**
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;

    /**
     * HomeController constructor.
     */
    public function __construct(
        UserLocationService $userLocationService,
        UserActivityService $userActivityService
    ) {
        parent::__construct($userActivityService);
        $this->userLocationService = $userLocationService;
    }

    public function directLogin(User $user, $routeName = 'home', $routeParamName = null, $routeParam = null)
    {
        Auth::login($user);

        if ($routeParamName) {
            return redirect()->route($routeName, [$routeParamName => $routeParam]);
        }

        return redirect()->route($routeName);
    }

    public function index()
    {
        if (!$this->authenticatedUser->getActive()) {
            $this->authenticatedUser->setActive(true);
            $this->authenticatedUser->setDeactivatedAt(null);
            $this->authenticatedUser->save();
        }

        $users = $this->homeUsers(30);

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in last 30 days within this radius. Going to 80km radius.');

            $users = $this->homeUsers(80);
        }

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in last 30 days. Going to 60 days.');

            $users = $this->homeUsers(60);

            if (count($users) < 10) {
                \Log::debug('Not enough recent bots with profile pic in last 60 days within this radius. Going to 80km radius.');

                $users = $this->homeUsers(60, 80);
            }
        }

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in 60 days either. Going to 100 days.');

            $users = $this->homeUsers(100);

            if (count($users) < 10) {
                \Log::debug('Not enough recent bots with profile pic in last 100 days within this radius. Going to 80km radius.');

                $users = $this->homeUsers(100, 80);
            }
        }

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in last 100 days within 80km radius. Fetching anything :)');

            $users = $this->homeUsers(200, 150);
        }

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in last 100 days within 100km radius. Showing latest users');

            $users = User::join('user_meta as um', 'users.id', '=', 'um.user_id')
                ->select('users.*')
                ->with(['meta', 'profileImage'])
                ->whereHas('profileImage')
                ->whereHas('roles', function ($query) {
                    $query->where('id', User::TYPE_BOT);

                    if ($this->authenticatedUser->meta->looking_for_gender === User::GENDER_MALE) {
                        $query->orWhere('id', User::TYPE_PEASANT);
                    }
                })
                ->whereHas('meta', function ($query) {
                    $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                    $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());
                })
                ->whereDoesntHave('meta', function ($query) {
                    $query->where('too_slutty_for_ads', true);
                })
                ->where('users.active', true)
                ->where('users.created_at', '>=',
                    Carbon::now('Europe/Amsterdam')->subDays(300)->setTimezone('UTC')
                )
                ->orderByRaw('RAND()')
                ->take(10);
        }

        return view('frontend.sites.' . config('app.directory_name') . '.home', [
            'title' => ucfirst(\config('app.name')) . ' - Dashboard',
            'users' => $users,
            'carbonNow' => Carbon::now(),
        ]);
    }

    private function homeUsers($createdUntilDaysAgo, $radius = 70)
    {
        if ($this->authenticatedUser->meta->lat && $this->authenticatedUser->meta->lng) {
            $lat = $this->authenticatedUser->meta->lat;
            $lng = $this->authenticatedUser->meta->lng;
        } else {
            $userCoordinates = $this->userLocationService->getCoordinatesForUser($this->authenticatedUser);

            $lat = $userCoordinates['lat'];
            $lng = $userCoordinates['lng'];
        }

        $latInRadians = deg2rad($lat);
        $lngInRadians = deg2rad($lng);

        $angularRadius = $radius / 6371;

        $latMin = rad2deg($latInRadians - $angularRadius);
        $latMax = rad2deg($latInRadians + $angularRadius);

        $deltaLng = asin(sin($angularRadius) / cos($latInRadians));

        $lngMin = rad2deg($lngInRadians - $deltaLng);
        $lngMax = rad2deg($lngInRadians + $deltaLng);

        $query = User::join('user_meta as um', 'users.id', '=', 'um.user_id')
            ->select('users.*')
            ->with(['meta', 'profileImage'])
            ->whereHas('profileImage')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);

                if ($this->authenticatedUser->meta->looking_for_gender === User::GENDER_MALE) {
                    $query->orWhere('id', User::TYPE_PEASANT);
                }
            })
            ->whereHas('meta', function ($query) use (
                $latMin,
                $latMax,
                $lngMin,
                $lngMax
            ) {
                $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());

                $query->where('lat', '>=', $latMin)
                    ->where('lat', '<=', $latMax)
                    ->where('lng', '>=', $lngMin)
                    ->where('lng', '<=', $lngMax);
            })
            ->whereDoesntHave('meta', function ($query) {
                $query->where('too_slutty_for_ads', true);
            })
            ->where('users.active', true)
            ->where('users.created_at', '>=',
                Carbon::now('Europe/Amsterdam')->subDays($createdUntilDaysAgo)->setTimezone('UTC')
            )
            ->orderByRaw('RAND()')
            ->take(10);

        return $query->get();
    }
}
