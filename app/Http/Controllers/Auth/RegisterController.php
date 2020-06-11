<?php  ?><?php

namespace App\Http\Controllers\Auth;

use App\EmailType;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\Welcome;
use App\Managers\AffiliateManager;
use App\RoleUser;
use App\Traits\Users\RegistersUsers;
use App\User;
use App\UserAccount;
use App\UserMeta;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ReCaptcha\ReCaptcha;
use GuzzleHttp\Psr7;

/**
 * Class RegisterController
 * @package App\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var AffiliateManager
     */
    private AffiliateManager $affiliateManager;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AffiliateManager $affiliateManager
    ) {
        $this->middleware('guest');
        $this->affiliateManager = $affiliateManager;
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
            $userIp = $this->getUserIp();
            $userMetaInstance = new UserMeta([
                'user_id' => $createdUser->id,
                'country' => 'nl',
                'gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$gender],
                'looking_for_gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$lookingFor],
                'registration_ip' => $userIp
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
        if ($request->input('clickId')) {
            try {
                $this->affiliateManager->storeAffiliateTrackingInfo(
                    $createdUser->id,
                    $request->input('affiliate'),
                    $request->input('clickId'),
                    $this->getLocationFromIp($userIp),
                    $request->input('mediaId'),
                );
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


    protected function getUserIp()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) { $ip = $client; }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) { $ip = $forward; }
        else { $ip = $remote; }

        return $ip;
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

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:15',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'active' => 1,
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(60),
        ]);
    }
}
