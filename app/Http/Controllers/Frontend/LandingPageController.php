<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\User;
use Carbon\Carbon;

class LandingPageController extends FrontendController
{
    public function show()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'peasant')
                ->orWhere('name', 'bot');
        })
            ->inRandomOrder()->take(6)->get();

        $testimonials = [
            [
                'quote' => 'Thank you for helping me find my soul mate. You made the process of finding someone special very easy and fun. I will recommend this site to all my friends.',
                'imgSource' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZMuP15kIk0xs6E5c2qgb5O7_AUeSxVVTWenaLaCU7wvPaELV_',
                'names' => 'Ann & Tom Black'
            ],
            [
                'quote' => 'Thank you for helping me find my soul mate. You made the process of finding someone special very easy and fun. I will recommend this site to all my friends.',
                'imgSource' => 'https://www.gannett-cdn.com/-mm-/c15f5f19a52dc82a8ae04998311685d79708bb7f/c=0-160-361-521/local/-/media/2017/12/21/INGroup/Indianapolis/636494825523802717-couple-1.png?width=200&height=200&fit=crop',
                'names' => 'Kate & John'
            ]
        ];

        return view(
            'frontend.landing-pages.1',
            [
                'title' => config('app.name'),
                'users' => $users,
                'carbonNow' => Carbon::now(),
                'testimonials' => $testimonials
            ]
        );
    }
}
