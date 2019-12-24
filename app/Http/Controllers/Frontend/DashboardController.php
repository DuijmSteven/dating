<?php

namespace App\Http\Controllers\Frontend;

use App\Activity;
use App\Http\Requests\Request;
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Activity $activity)
    {
        return view('frontend.home', [
            'title' => config('app.name') . ' - Dashboard',
            'activity' => $activity->latest()->get()
        ]);
    }

    /**
     * Show the contact view
     *
     * @return \Illuminate\Http\Response
     */
    public function showContact()
    {
        return view(
            'frontend.contact',
            [
                'title' => config('app.name') . ' - Contact',
            ]
        );
    }
}
