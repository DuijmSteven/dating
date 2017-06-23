<?php

namespace App\Http\Controllers\Frontend;

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
    public function index()
    {
        $this->setupSidebars(true);

        return view('frontend.home', [
            'title' => 'Homepage - ' . config('app.name'),
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
