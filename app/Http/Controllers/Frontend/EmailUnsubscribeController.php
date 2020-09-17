<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmailUnsubscribeController extends FrontendController
{
    /*public function index($email = null, $token = null)
    {
        $email = urldecode($email);
        $token = base64_decode($token);
        if(Hash::check($email, $token)) {
            return view(
                'frontend.unsubscribe',
                [
                    'email' => $email,
                    'token' => $token
                ]
            );
        } else {
            return view('frontend.unsubscribe',
                [
                    'error' => true
                ]
            );
        }
    }*/

    public function index(User $user)
    {
        return view(
            'frontend.unsubscribe',
            [
                'user' => $user
            ]
        );
    }

    public function unsubscribe(User $user)
    {
        $user = User::with('emailTypes')->where('id', $user->id)->get()[0];

        if($user) {
            $user->emailTypes()->detach();
            $user->save();

            return view('frontend.unsubscribe',
                [
                    'success' => true
                ]
            );
        } else {
            return view('frontend.unsubscribe',
                [
                    'error' => true
                ]
            );
        }
    }
}
