<?php

namespace App\Traits\Users;

use App\UserAffiliateTracking;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Psr7;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.

     */
    protected function registered(Request $request, $user)
    {
        if ($user->affiliateTracking()->exists() && $user->affiliateTracking->affiliate === UserAffiliateTracking::AFFILIATE_XPARTNERS) {
            $genderLookingForGender = explode("-", $request->all()['lookingFor']);
            $gender = $genderLookingForGender[0];
            $clientIP = $this->userLocationService->getUserIp();
            $countryCode = $this->userLocationService->getLocationFromIp($clientIP);
            $client = new Client();
            try {
                $response = $client->request(
                    'GET',
                    'https://mt67.net/d/?bdci='. $user->affiliateTracking->getClickId() .'&ti=' . $user->id . '&pn=lead-XP-Altijdsex.nl&iv=media-' . $user->affiliateTracking->getMediaId() . '&c=' . $countryCode .'&g=' . $gender . '&cc=lead',
                    [
                        'timeout' => 4
                    ]
                );
            } catch (RequestException $e) {
                \Log::error('Affiliate postback error - ' . Psr7\str($e->getRequest()));
                if ($e->hasResponse()) {
                    \Log::error('Affiliate postback error - ' . Psr7\str($e->getResponse()));
                }
            }
        }
    }
}
