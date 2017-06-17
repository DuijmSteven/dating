<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

/**
 * Class HomeController
 * @package App\Http\Controllers\Frontend
 */
class HomeController extends Controller
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
        return view('frontend.home', [
            'title' => 'Homepage - ' . config('app.name'),
            'hasRightSidebar' => true,
            'hasLeftSidebar' => true,
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
                'hasRightSidebar' => true
            ]
        );
    }
}
