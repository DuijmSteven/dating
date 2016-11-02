<?php

namespace App\Http\Requests\UserRequests;

use App\Http\Requests\Request;

class UserSearchRequest extends Request
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
        return [
            'query' => 'max:50',
            'username' => 'max:50|string',
            'city' => 'string|max:50',
            'province' => 'in:'. implode($userProfileFields['province'], ','),
            'gender' => 'in:'. implode($userProfileFields['gender'], ','),
            'relationship_status' => 'in:'. implode($userProfileFields['relationship_status'], ','),
            'body_type' => 'in:'. implode($userProfileFields['body_type'], ','),
            'eye_color' => 'in:'. implode($userProfileFields['eye_color'], ','),
            'hair_color' => 'in:'. implode($userProfileFields['hair_color'], ','),
            'drinking_habits' => 'in:'. implode($userProfileFields['smoking_habits'], ','),
            'smoking_habits' => 'in:'. implode($userProfileFields['drinking_habits'], ','),
            'about_me' => 'string|max:100',
            'looking_for' => 'string|max:100',
            'role' => 'in:'. implode(array_keys(\UserConstants::ROLES))
        ];
    }
}
