<?php

namespace App\Http\Requests\Backend\Bots;

use App\Http\Requests\Request;

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
        $userProfileFields = \UserConstants::PROFILE_FIELDS;

        $rules = [
            'username' => 'min:5|max:50|string|required|unique:users,username,' . trim($this->route('id') . ',id'),
            'active' => 'boolean',
            'dob' => 'date_format:Y-m-d',
            'gender' => 'in:'. implode($userProfileFields['gender'], ','),
            'relationship_status' => 'in:'. implode($userProfileFields['relationship_status'], ','),
            'body_type' => 'in:'. implode($userProfileFields['body_type'], ','),
            'eye_color' => 'in:'. implode($userProfileFields['eye_color'], ','),
            'height' => 'in:'. implode($userProfileFields['height'], ','),
            'hair_color' => 'in:'. implode($userProfileFields['hair_color'], ','),
            'smoking_habits' => 'in:'. implode($userProfileFields['drinking_habits'], ','),
            'drinking_habits' => 'in:'. implode($userProfileFields['smoking_habits'], ','),
            'province' => 'in:'. implode($userProfileFields['province'], ','),
            'city' => 'string|min:3|max:40',
            'about_me' => 'string|max:1000',
            'looking_for' => 'string|max:1000'
        ];

        $imageCount = count($this->input('user_images')) - 1;
        foreach (range(0, $imageCount) as $index) {
            $rules['user_images.' . $index] = 'image|max:4000';
        }

        return $rules;
    }
}
