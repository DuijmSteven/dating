<?php

namespace App\Http\Controllers\Frontend;

use App\Creditpack;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreditsController extends FrontendController
{
    public function show()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', User::TYPE_BOT);
        })
            ->whereHas('meta', function ($query) {
                $query->where(
                    'looking_for_gender',
                    $this->authenticatedUser->meta->getGender()
                )->where(
                    'gender',
                    $this->authenticatedUser->meta->getLookingForGender()
                );;
            })
            ->whereHas('profileImage')
            ->where('id', '!=', $this->authenticatedUser->getId())
            ->inRandomOrder()->take(6)->get();

        if (!$this->authenticatedUser->getHasNewPricing()) {
            $creditpacks = Creditpack::whereIn('id', [1, 2, 3, 5])->orderBy('id')->get();
        } else {
            $creditpacks = Creditpack::whereIn('id', [6, 7, 8, 9, 10])->orderBy('id')->get();
        }

        return view('frontend.sites.' . config('app.directory_name') . '.credits',
            [
                'title' => $this->buildTitleWith(trans(config('app.directory_name') . '/view_titles.credits')),
                'users' => $users,
                'carbonNow' => Carbon::now(),
                'creditpacks' => $creditpacks
            ]
        );
    }
}
