<?php

namespace App\Http\Controllers\Frontend;


use App\User;
use Carbon\Carbon;

class LandingPageController extends FrontendController
{
    public function show()
    {
        $users = User::orderBy('created_at', 'desc')->take(6)->get();

        return view(
            'frontend.landing-pages.1',
            [
                'title' => config('app.name'),
                'users' => $users,
                'carbonNow' => Carbon::now()
            ]
        );
    }
}
