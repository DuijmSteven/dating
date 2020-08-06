<?php

namespace App\Http\Controllers\Frontend;

use App\Activity;
use App\Http\Requests\Request;
use App\Services\UserLocationService;
use App\User;
use Carbon\Carbon;
use Hash;
use Stevebauman\Location\Facades\Location;

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
        UserLocationService $userLocationService
    )
    {
        parent::__construct();
        $this->userLocationService = $userLocationService;
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

            $users = $this->homeUsers(150, 150);
        }

        return view('frontend.home', [
            'title' => config('app.name') . ' - Dashboard',
            'users' => $users,
            'carbonNow' => Carbon::now(),
        ]);
    }

    private function homeUsers($createdUntilDaysAgo, $radius = 70)
    {
        $userCoordinates = $this->userLocationService->getCoordinatesForUser($this->authenticatedUser);

        $lat = $userCoordinates['lat'];
        $lng = $userCoordinates['lng'];

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
            ->where('users.active', true)
            ->where('users.created_at', '>=',
                Carbon::now('Europe/Amsterdam')->subDays($createdUntilDaysAgo)->setTimezone('UTC')
            )
            ->orderByRaw('RAND()')
            ->take(10);

        return $query->get();
    }
}
