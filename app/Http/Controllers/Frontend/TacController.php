<?php

namespace App\Http\Controllers\Frontend;

use App\Tac;
use Illuminate\Support\Facades\Config;

class TacController extends FrontendController
{
    public function show()
    {
        return view(
            'frontend.tac.show',
            [
                'title' => 'Terms & Conditions - ' . \config('app.name'),
                'tac' => Tac::where('language', Config::get('app.locale'))->first()
            ]
        );
    }
}
