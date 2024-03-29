<?php

namespace App\Http\Requests\Admin\Peasants;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeasantUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userProfileFields = UserConstants::selectableFields('peasant');

        $userCountryCode = Auth::user()->meta->country;

        if (!in_array($userCountryCode, ['nl', 'be'])) {
            $countryCode = 'nl';
        } else {
            $countryCode = Auth::user()->meta->country;
        }

        $rules = [
            //'username' => 'min:5|max:50|string|unique:users,username,' . trim($this->route('userId') . ',id'),
            'active' => 'boolean',
            'email' => 'required|email|unique:users,email,' . trim($this->route('userId') . ',id'),
            'dob' => 'date_format:d-m-Y|before:' . Carbon::now('Europe/Amsterdam')->subYears(18)->format('d-m-Y') . '|after:' . Carbon::now('Europe/Amsterdam')->subYears(100)->format('d-m-Y'),
            'gender' => 'in:'. implode(',', array_keys($userProfileFields['gender'])),
            'looking_for_gender' => 'in:'. implode(',', array_keys($userProfileFields['looking_for_gender'])),
            'relationship_status' => 'in:'. implode(',', array_keys($userProfileFields['relationship_status'])),
            'body_type' => 'in:'. implode(',', array_keys($userProfileFields['body_type'])),
            'eye_color' => 'in:'. implode(',', array_keys($userProfileFields['eye_color'])),
            'height' => 'in:'. implode(',', array_keys($userProfileFields['height'])),
            'hair_color' => 'in:'. implode(',', array_keys($userProfileFields['hair_color'])),
            'smoking_habits' => 'in:'. implode(',', array_keys($userProfileFields['drinking_habits'])),
            'drinking_habits' => 'in:'. implode(',', array_keys($userProfileFields['smoking_habits'])),
            'city' => 'in:' . implode(',', UserConstants::$cities[$countryCode]),
            'about_me' => 'string|max:1000',
            'profile_image' => 'mimes:jpeg,png,jpg|max:10000',
        ];

        if (!is_null($this->files->get('user_images'))) {

            $imageCount = count($this->files->get('user_images')) - 1;
            foreach (range(0, $imageCount) as $index) {
                $rules['user_images.' . $index] = 'mimes:jpeg,png,jpg|max:10000';
            }
        }
        return $rules;
    }
}
