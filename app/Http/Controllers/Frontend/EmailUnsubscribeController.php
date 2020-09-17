<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmailUnsubscribeController extends FrontendController
{
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
