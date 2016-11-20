<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $viewData = [
        ];

        return view('backend.dashboard', array_merge(
            $viewData,
            [
                'title' => 'Dashboard - ' . \MetaConstants::SITE_NAME,
                'headingLarge' => 'Dashboard',
            ]
        ));
    }
}
