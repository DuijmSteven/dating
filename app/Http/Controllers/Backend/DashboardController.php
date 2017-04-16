<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
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
                'title' => 'Dashboard - ' . \MetaConstants::$siteName,
                'headingLarge' => 'Dashboard',
            ]
        ));
    }
}
