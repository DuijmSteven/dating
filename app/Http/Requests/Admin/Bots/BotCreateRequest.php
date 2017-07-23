<?php

namespace App\Http\Requests\Admin\Bots;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class BotCreateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class BotCreateRequest extends Request
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
            'username' => 'min:5|max:50|string|required|unique:users',
            'password' => 'required|min:6',
            'active' => 'required|boolean',
            'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required|in:'. implode(array_keys($userProfileFields['gender']), ','),
            'looking_for_gender' => 'required|in:'. implode(array_keys($userProfileFields['looking_for_gender']), ','),
            'relationship_status' => 'in:'. implode(array_keys($userProfileFields['relationship_status']), ','),
            'body_type' => 'in:'. implode(array_keys($userProfileFields['body_type']), ','),
            'eye_color' => 'in:'. implode(array_keys($userProfileFields['eye_color']), ','),
            'height' => 'in:'. implode(array_keys($userProfileFields['height']), ','),
            'hair_color' => 'in:'. implode(array_keys($userProfileFields['hair_color']), ','),
            'smoking_habits' => 'in:'. implode(array_keys($userProfileFields['drinking_habits']), ','),
            'drinking_habits' => 'in:'. implode(array_keys($userProfileFields['smoking_habits']), ','),
            'province' => 'in:'. implode(array_keys($userProfileFields['province']), ','),
            'city' => 'string|min:3|max:40',
            'about_me' => 'string|max:1000',
            'looking_for' => 'string|max:1000',
            'profile_image' => 'image|max:4000',
        ];

        $imageCount = count($this->input('user_images')) - 1;
        foreach (range(0, $imageCount) as $index) {
            $rules['user_images.' . $index] = 'image|max:4000';
        }

        return $rules;
    }
}
