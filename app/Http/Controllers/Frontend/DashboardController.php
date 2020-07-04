<?php

namespace App\Http\Controllers\Frontend;

use App\Activity;
use App\Http\Requests\Request;
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
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $lastThirtyDays = Carbon::now('Europe/Amsterdam')->subDays(30)->setTimezone('UTC');

        $users = User::with(['meta', 'profileImage'])
            ->whereHas('profileImage')
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());
            })
            ->where('active', true)
            ->where('created_at', '>=', $lastThirtyDays)
            ->orderByRaw('RAND()')
            ->take(10)
            ->get();

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in last 30 days. Going to 30 days.');

            $lastSixtyDays = Carbon::now('Europe/Amsterdam')->subDays(60)->setTimezone('UTC');

            $users = User::with(['meta', 'profileImage'])
                ->whereHas('profileImage')
                ->whereHas('roles', function ($query) {
                    $query->where('id', User::TYPE_BOT);
                })
                ->whereHas('meta', function ($query) {
                    $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                    $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());
                })
                ->where('active', true)
                ->where('created_at', '>=', $lastSixtyDays)
                ->orderByRaw('RAND()')
                ->take(10)
                ->get();
        }

        if (count($users) < 10) {
            \Log::debug('Not enough recent bots with profile pic in 60 days either. Going to 100 days.');

            $lastSixtyDays = Carbon::now('Europe/Amsterdam')->subDays(100)->setTimezone('UTC');

            $users = User::with(['meta', 'profileImage'])
                ->whereHas('profileImage')
                ->whereHas('roles', function ($query) {
                    $query->where('id', User::TYPE_BOT);
                })
                ->whereHas('meta', function ($query) {
                    $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                    $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());
                })
                ->where('active', true)
                ->where('created_at', '>=', $lastSixtyDays)
                ->orderByRaw('RAND()')
                ->take(10)
                ->get();
        }

        return view('frontend.home', [
            'title' => config('app.name') . ' - Dashboard',
            'users' => $users,
            'carbonNow' => Carbon::now(),
        ]);
    }
}
