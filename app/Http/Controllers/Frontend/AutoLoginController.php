<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use Illuminate\Support\Facades\Auth;

class AutoLoginController extends FrontendController
{
    public function login(User $user)
    {
        Auth::login($user);

        return redirect()->home();
    }
}
