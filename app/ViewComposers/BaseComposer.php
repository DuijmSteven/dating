<?php

namespace App\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\User;

class BaseComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (Auth::user()) {
            $fieldsToSelect = [
                'users.id as user_id',
                'users.username',
                'users.email',
                'users.active',
                'user_meta.looking_for',
                'user_meta.about_me',
                'user_meta.dob',
                'user_meta.gender',
                'user_meta.relationship_status',
                'user_meta.city',
                'user_meta.province',
                'user_meta.height',
                'user_meta.body_type',
                'user_meta.eye_color',
                'user_meta.hair_color',
                'user_meta.smoking_habits',
                'user_meta.drinking_habits',
                'user_meta.country',
                'user_images.filename as image_name',
                'user_images.visible as image_visible',
                'user_images.profile as image_profile',
            ];

            $authenticatedUser = User::select($fieldsToSelect)
                ->join('user_meta', 'user_meta.user_id', 'users.id')
                ->leftJoin('user_images', 'user_images.user_id', 'users.id')
                ->groupBy('image_name')
                ->where('users.id', Auth::user()->id)
                ->first();

            \Log::info($authenticatedUser);
            die();
        }

        $view->with('authenticatedUser', Auth::user());
    }
}
