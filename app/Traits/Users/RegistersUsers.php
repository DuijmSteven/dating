<?php

namespace App\Traits\Users;

use Illuminate\Foundation\Auth\RedirectsUsers;
use App\Peasant;
use App\PeasantMeta;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        DB::beginTransaction();
        try {
            /** @var Peasant $createdPeasant */
            $createdPeasant = $this->create($request->all());

            \Log::debug($createdPeasant->toArray());
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        try {
            /** @var PeasantMeta $peasantMetaInstance */
            $peasantMetaInstance = new PeasantMeta([
                'user_id' => $createdPeasant->id,
                'country' => 'nl'
            ]);

            \Log::debug($peasantMetaInstance->toArray());

            $peasantMetaInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        try {
            /** @var RoleUser $roleUserInstance */
            $roleUserInstance = new RoleUser([
                'role_id' => \UserConstants::ROLES['user'],
                'user_id' => $createdPeasant->id
            ]);

            $roleUserInstance->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();

        $this->guard()->login($createdPeasant);

        return $this->registered($request, $createdPeasant)
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
