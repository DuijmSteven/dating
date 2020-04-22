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

        return view('frontend.home', [
            'title' => config('app.name') . ' - Dashboard',
            'users' => User::with(['meta', 'profileImage'])
            ->whereHas('roles', function ($query) {
                $query->where('id', User::TYPE_BOT);
                $query->orWhere('id', User::TYPE_PEASANT);
            })
            ->whereHas('meta', function ($query) {
                $query->where('gender', $this->authenticatedUser->meta->getLookingForGender());
                $query->where('looking_for_gender', $this->authenticatedUser->meta->getGender());
            })
            ->where('active', true)
            ->where('created_at', '>=', $lastThirtyDays)
            ->orderByRaw('RAND()')
            ->take(10)
            ->get()
        ]);
    }
}
