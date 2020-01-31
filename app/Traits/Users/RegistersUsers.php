<?php

namespace App\Traits\Users;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\RegisterRequest;
use App\Mail\Contact;
use App\Mail\Welcome;
use App\Services\GeocoderService;
use App\User;
use App\UserAccount;
use App\UserMeta;
use App\RoleUser;
use Carbon\Carbon;
use Cornford\Googlmapper\Mapper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $lat = $request->all()['lat'];
        $lng = $request->all()['lng'];

        if (!$lat || $lat == 0 || is_null($lat) || !$lng || $lng == 0 || is_null($lng)) {

            \Log::debug('User ID: ' . $createdUser->getId() . ' - Registration attempt without coordinates');

            $client = new Client();
            $geocoder = new GeocoderService($client);

            $coordinates = $geocoder->getCoordinatesForAddress($request->all()['city']);

            $lat = $coordinates['lat'];
            $lng = $coordinates['lng'];
        }

        try {
            /** @var UserMeta $userMetaInstance */
            $userMetaInstance = new UserMeta([
                'user_id' => $createdUser->id,
                'country' => 'nl',
                'gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$gender],
                'looking_for_gender' => UserConstants::selectableField('gender', 'peasant', 'array_flip')[$lookingFor],
                'dob' =>  new Carbon($request->all()['dob']),
                'lat' => $lat,
                'lng' => $lng,
                'city' => $request->all()['city'],
            ]);

            $userMetaInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        try {
            /** @var UserAccount $userAccountInstance */
            $userAccountInstance = new UserAccount([
                'user_id' => $createdUser->id,
                'credits' => 1
            ]);

            $userAccountInstance->save();
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
        DB::commit();

        $welcomeEmail = (new    Welcome($createdUser))->onQueue('emails');

        Mail::to($createdUser)
            ->queue($welcomeEmail);

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
        //
    }
}
