<?php

namespace App\Traits\Users;

use App\EmailType;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\RegisterRequest;
use App\Mail\Contact;
use App\Mail\Welcome;
use App\Milestone;
use App\Services\GeocoderService;
use App\User;
use App\UserAccount;
use App\UserAffiliateTracking;
use App\UserEmailTypeInstance;
use App\UserMeta;
use App\RoleUser;
use Carbon\Carbon;
use Cornford\Googlmapper\Mapper;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use ReCaptcha\ReCaptcha;

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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        } else {
            $captcha = false;
        }

        if (!$captcha) {
            throw new \Exception('no captcha');
        }

        $response = (new ReCaptcha(config('app.recaptcha_secret')))
            ->setExpectedAction('register')
            ->verify($captcha, $request->ip());

        if (!$response->isSuccess()) {
            \Log::info('Failed recaptcha attempt from username: ' . $request->get('username') . ' and email: ' . $request->get('email'));
            return redirect()->back()->with('recaptchaFailed', true);
        }

        if ($response->getScore() < 0.6) {
            \Log::info('Recaptcha attempt with small score from username: ' . $request->get('username') . ' and email: ' . $request->get('email'));
            return redirect()->back()->with('recaptchaFailed', true);
        }

        $genderLookingForGender = explode("-", $request->all()['lookingFor']);
        $gender = $genderLookingForGender[0];
        $lookingFor = $genderLookingForGender[1];

        DB::beginTransaction();
        try {
            /** @var User $createdUser */
            $createdUser = $this->create($request->all());
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

//        $client = new Client();
//        $geocoder = new GeocoderService($client);
//
//        $coordinates = $geocoder->getCoordinatesForAddress($request->all()['city']);
//
//        $lat = $coordinates['lat'];
//        $lng = $coordinates['lng'];

        try {
            /** @var UserMeta $userMetaInstance */
            $userMetaInstance = new UserMeta([
                'user_id' => $createdUser->id,
                'country' => 'nl',
                'gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$gender],
                'looking_for_gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$lookingFor],
                //'dob' =>  new Carbon($request->all()['dob']),
//                'lat' => $lat,
//                'lng' => $lng,
//                'city' => $request->all()['city'],
            ]);

            $userMetaInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        //Set the affiliate tracking data
        if ($request->input('mediaId') && $request->input('clickId')) {
            try {
                /** @var UserAffiliateTracking $userAffiliateTrackingInstance */
                $userAffiliateTrackingInstance = new UserAffiliateTracking([
                    'user_id' => $createdUser->id,
                    'media_id' => $request->input('mediaId'),
                    'click_id' => $request->input('clickId'),
                ]);

                $userAffiliateTrackingInstance->save();
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        }

        try {
            $amountOfFreeCredits = 1;

            /** @var UserAccount $userAccountInstance */
            $userAccountInstance = new UserAccount([
                'user_id' => $createdUser->id,
                'credits' => $amountOfFreeCredits
            ]);

            $userAccountInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $createdUser->emailTypes()->attach(EmailType::MESSAGE_RECEIVED);
            $createdUser->emailTypes()->attach(EmailType::PROFILE_VIEWED);
            $createdUser->emailTypes()->attach(EmailType::GENERAL);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            /** @var RoleUser $roleUserInstance */
            $roleUserInstance = new RoleUser([
                'role_id' => \UserConstants::selectableField('role', 'common', 'array_flip')['peasant'],
                'user_id' => $createdUser->id
            ]);

            $roleUserInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            $welcomeEmail = (new Welcome($createdUser))->onQueue('emails');

            Mail::to($createdUser)
                ->queue($welcomeEmail);

            $createdUser->emailTypeInstances()->attach(EmailType::WELCOME, [
                'email' => $createdUser->getEmail(),
                'email_type_id' => EmailType::WELCOME,
                'actor_id' => null
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();

        $this->guard()->login($createdUser);

        return $this->registered($request, $createdUser)
            ?: redirect($this->redirectPath());
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //In case the registration came from an affiliate, hit publisher callback
        if ($user->affiliateTracking()->exists()) {
            $genderLookingForGender = explode("-", $request->all()['lookingFor']);
            $gender = $genderLookingForGender[0];
            $clientIP = \Request::ip();
            $countryCode = $this->getLocationFromIp($clientIP);
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

    protected function getLocationFromIp($ip)
    {
        $client = new Client();
        try {
            $response = $client->request(
                'GET',
                'http://api.ipstack.com/' . $ip . '?access_key=b56f13e4f12b980317694de933fd340d',
                [
                    'timeout' => 4
                ]
            );
            $response = json_decode($response->getBody(), true);
            return $response['country_code'];
        } catch (RequestException $e) {
            \Log::error('Cannot get IP - ' . Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                \Log::error('Cannot get IP - ' . Psr7\str($e->getResponse()));
            }
        }
    }
}
