<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreditsController extends FrontendController
{
    public function show()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'peasant')
                ->orWhere('name', 'bot');
        })
            ->whereHas('meta', function ($query) {
                $query->where(
                    'looking_for_gender',
                    $this->authenticatedUser->meta->getGender()
                )->where(
                    'gender',
                    $this->authenticatedUser->meta->getLookingForGender()
                );;
            })
            ->where('id', '!=', $this->authenticatedUser->getId())
            ->inRandomOrder()->take(6)->get();

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
