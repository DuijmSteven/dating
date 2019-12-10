<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreditsController extends FrontendController
{
    public function show()
    {
        // TODO hardcode specific selection of users here
        $users = User::orderBy('created_at', 'asc')->take(6)->get();

        return view('frontend.credits',
            [
                'title' => config('app.name'),
                'users' => $users,
                'carbonNow' => Carbon::now(),
                'creditpacks' => Creditpack::all()->sortBy('id')
            ]
        );
    }
}
