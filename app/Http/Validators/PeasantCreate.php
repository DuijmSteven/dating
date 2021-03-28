<?php

namespace App\Http\Validators;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Carbon\Carbon;

class PeasantCreate extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules($files)
    {
        $userProfileFields = UserConstants::selectableFields('bot');

        $rules = [
            'username' => 'without_spaces|min:5|max:25|string|required|unique:users',
            'password' => 'required|min:8',
            'active' => 'required|boolean',
            'dob' => 'date_format:d-m-Y|before:' . Carbon::now('Europe/Amsterdam')->subYears(18)->format('d-m-Y') . '|after:' . Carbon::now('Europe/Amsterdam')->subYears(100)->format('d-m-Y'),
            'gender' => 'required|in:'. implode(',', array_keys($userProfileFields['gender'])),
            'looking_for_gender' => 'required|in:'. implode(',', array_keys($userProfileFields['looking_for_gender'])),
            'relationship_status' => 'in:'. implode(',', array_keys($userProfileFields['relationship_status'])),
            'body_type' => 'in:'. implode(',', array_keys($userProfileFields['body_type'])),
            'eye_color' => 'in:'. implode(',', array_keys($userProfileFields['eye_color'])),
            'height' => 'in:'. implode(',', array_keys($userProfileFields['height'])),
            'hair_color' => 'in:'. implode(',', array_keys($userProfileFields['hair_color'])),
            'smoking_habits' => 'in:'. implode(',', array_keys($userProfileFields['drinking_habits'])),
            'drinking_habits' => 'in:'. implode(',', array_keys($userProfileFields['smoking_habits'])),
            'city' => 'in:' . implode(',', array_merge(UserConstants::$cities['nl'], UserConstants::$cities['be'])),
            'country' => 'required|in:be,nl',
            'about_me' => 'string|max:1000',
            'looking_for' => 'string|max:1000',
            'profile_image' => 'image|max:4000',
        ];

        if (!is_null($files->get('user_images'))) {
            $imageCount = count($files->get('user_images')) - 1;
            foreach (range(0, $imageCount) as $index) {
                $rules['user_images.' . $index] = 'image|max:4000';
            }
        }

        return $rules;
    }
}
