<?php

namespace App\Http\Controllers\Frontend;

use App\Activity;

/**
 * Class HomeController
 * @package App\Http\Controllers\Frontend
 */
class HomeController extends FrontendController
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
            'title' => 'Homepage - ' . config('app.name'),
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
                'title' => 'Contact - ' . config('app.name'),
            ]
        );
    }
}
