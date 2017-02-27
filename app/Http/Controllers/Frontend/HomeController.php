<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend/home');
    }

    /**
     * Show the contact view
     *
     * @return \Illuminate\Http\Response
     */
    public function showContact()
    {
        return view(
            'frontend/contact',
            [
                'title' => 'Contact - ' . config('app.name'),
                'hasSidebar' => true
            ]
        );
    }
}
