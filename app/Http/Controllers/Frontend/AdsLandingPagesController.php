<?php

namespace App\Http\Controllers\Frontend;


use App;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;
use function foo\func;

class AdsLandingPagesController extends FrontendController
{
    public function showLP(Request $request, $id)
    {
        if(view()->exists('frontend.landing-pages.ads.' . $id)) {
            return view(
                'frontend.landing-pages.ads.' . $id,
                [
                    'mediaId' => $request->input('utm_campaign'),
                    'clickId' => $request->input('clid')
                ]
            );
        } else {
            return abort(404);
        }
    }
}
