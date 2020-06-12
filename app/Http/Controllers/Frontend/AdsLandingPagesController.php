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

            $viewData = [];

            if ($request->input('utm_campaign')) {
                $viewData['mediaId'] = $request->input('utm_campaign');
            }

            if ($request->input('clid')) {
                $viewData['clickId'] = $request->input('clid');
                $viewData['affiliate'] = App\UserAffiliateTracking::AFFILIATE_XPARTNERS;
            } elseif ($request->input('gclid')) {
                $viewData['clickId'] = $request->input('gclid');
                $viewData['affiliate'] = App\UserAffiliateTracking::AFFILIATE_GOOGLE;
            }

            return view(
                'frontend.landing-pages.ads.' . $id,
                $viewData
            );
        } else {
            return abort(404);
        }
    }
}
