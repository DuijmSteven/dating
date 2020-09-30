<?php  ?><?php

namespace App\Http\Controllers\Auth;

use App\EmailType;
use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\Welcome;
use App\Managers\AffiliateManager;
use App\RoleUser;
use App\Services\EmailVerificationService;
use App\Services\UserLocationService;
use App\Traits\Users\RegistersUsers;
use App\User;
use App\UserAccount;
use App\UserAffiliateTracking;
use App\UserFingerprint;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use ReCaptcha\ReCaptcha;

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
     * @var UserLocationService
     */
    private UserLocationService $userLocationService;
    /**
     * @var EmailVerificationService
     */
    private EmailVerificationService $emailVerificationService;

    /**
     * RegisterController constructor.
     * @param AffiliateManager $affiliateManager
     * @param UserLocationService $userLocationService
     */
    public function __construct(
        AffiliateManager $affiliateManager,
        UserLocationService $userLocationService,
        EmailVerificationService $emailVerificationService
    ) {
        $this->middleware('guest');
        $this->affiliateManager = $affiliateManager;
        $this->userLocationService = $userLocationService;
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        \Log::info(1);

        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        } else {
            $captcha = false;
        }

        if (!$captcha) {
            \Log::debug('Recaptcha does not exist!');

            throw new \Exception('no captcha');
        }

        $userIp = $this->userLocationService->getUserIp();

        $response = (new ReCaptcha(config('app.recaptcha_secret')))
            ->setExpectedAction('register')
            ->verify($captcha, $userIp);

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

        $countryCode = null;


        $countryCode = $this->userLocationService->getCountryCodeFromIp($userIp);

        if ($userIp && $countryCode) {
            $countryCode = strtolower($countryCode);
        }

        try {
            /** @var UserMeta $userMetaInstance */
            $userMetaInstance = new UserMeta([
                'user_id' => $createdUser->id,
                'country' => $countryCode,
                'gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$gender],
                'looking_for_gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$lookingFor],
                'email_verified' => 0,
                'registration_lp_id' => (int) $request->get('registration_lp'),
                'registration_keyword' => $request->get('registration_keyword')
                //'dob' =>  new Carbon($request->all()['dob']),
//                'lat' => $lat,
//                'lng' => $lng,
//                'city' => $request->all()['city'],
            ]);

            $userMetaInstance->save();

//            $emailValidationResult = $this->emailVerificationService->verifySingleEmail($createdUser->getEmail());
//
//            $this->emailVerificationService->setUserMetaFromVerificationResult(
//                $createdUser,
//                $emailValidationResult
//            );

        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        //Set the affiliate tracking data
        if ($request->input('clickId')) {
            $mediaId = $request->input('mediaId');
            if (in_array($mediaId, array_keys(UserAffiliateTracking::publisherPerMediaId()))) {
                $publisher = UserAffiliateTracking::publisherPerMediaId()[$mediaId];
            } else {
                if ($request->input('affiliate') === UserAffiliateTracking::AFFILIATE_GOOGLE) {
                    if ($request->input('country') === 'nl') {
                        $publisher = UserAffiliateTracking::PUBLISHER_GOOGLE_NL;
                    } elseif ($request->input('country') === 'be') {
                        $publisher = UserAffiliateTracking::PUBLISHER_GOOGLE_BE;
                    } else {
                        \Log::debug('Country parameter: ' . $request->input('country'));

                        \Log::debug('Google ads registration with null country');

                        $publisher = null;
                    }
                } else {
                    $publisher = null;
                    \Log::debug('Media ID ' . $mediaId . ' does not have a publisher set');
                }
            }

            try {
                $this->affiliateManager->storeAffiliateTrackingInfo(
                    $createdUser->id,
                    $request->input('affiliate'),
                    $request->input('clickId'),
                    $countryCode,
                    $mediaId,
                    $publisher
                );
            } catch (\Exception $exception) {
                DB::rollBack();
                throw $exception;
            }
        } else if ($request->input('affiliate') && $request->input('affiliate') === UserAffiliateTracking::AFFILIATE_DATECENTRALE) {
            $this->affiliateManager->storeAffiliateTrackingInfo(
                $createdUser->id,
                $request->input('affiliate'),
                null,
                $countryCode,
                null,
                UserAffiliateTracking::PUBLISHER_DATECENTRALE
            );
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
            $createdUser->emailTypes()->attach(EmailType::GENERAL);
            // $createdUser->emailTypes()->attach(EmailType::PROFILE_VIEWED);
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

        Cookie::queue(Cookie::forget('affiliate'));
        Cookie::queue(Cookie::forget('clid'));
        Cookie::queue(Cookie::forget('gclid'));
        Cookie::queue(Cookie::forget('mediaId'));

        $this->guard()->login($createdUser);

        return $this->registered($request, $createdUser)
            ?: redirect($this->redirectPath());
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
            //'email' => 'required|email|max:255|unique:users',
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
        \Log::info($data);

        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'active' => 1,
            'password' => bcrypt($data['password']),
            'api_token' => Str::random(60),
        ]);
    }
}
