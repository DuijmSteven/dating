<?php

namespace App\Http\Controllers\Frontend;


class LandingPageController extends FrontendController
{
    public function show()
    {
        return view(
            'frontend.landing-pages.1',
            [
                'title' => config('app.name'),
            ]
        );
    }
}
