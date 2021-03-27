<?php

namespace App\Http\Requests\Admin\Bots;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Carbon\Carbon;

/**
 * Class BotUpdateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class BotUpdateRequest extends Request
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
        $userProfileFields = UserConstants::selectableFields('bot');

        $rules = [
            'username' => 'without_spaces|min:3|max:25|string|required|unique:users,username,' . trim($this->route('id') . ',id'),
            'active' => 'boolean',
            'too_slutty_for_ads' => 'boolean',
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
            'city' => 'in:' . implode(',', array_merge(UserConstants::$cities['nl'], UserConstants::$cities['be'])),
            'country' => 'required|in:be,nl',
            'about_me' => 'string|max:1000',
            'looking_for' => 'string|max:1000'
        ];

        if (!is_null($this->files->get('user_images'))) {

            $imageCount = count($this->files->get('user_images')) - 1;
            foreach (range(0, $imageCount) as $index) {
                $rules['user_images.' . $index] = 'image|max:4000';
            }
        }

        return $rules;
    }
}
