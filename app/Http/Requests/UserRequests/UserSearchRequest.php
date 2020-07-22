<?php

namespace App\Http\Requests\UserRequests;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class UserSearchRequest
 * @package App\Http\Requests\UserRequests
 */
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
        $userProfileFields = UserConstants::selectableFields('peasant', 'public');

        return [
            'query' => 'max:50',
            'username' => 'max:50|string',
            'city_name' => 'required|in:' . implode(',', array_merge(UserConstants::$cities['nl'], UserConstants::$cities['be'])),
            //'radius' => 'required_with:city_name|integer',
            'gender' => 'in:'. implode(',', array_keys($userProfileFields['gender'])),
            'relationship_status' => 'in:'. implode(',', array_keys($userProfileFields['relationship_status'])),
            'body_type' => 'in:'. implode(',', array_keys($userProfileFields['body_type'])),
            'eye_color' => 'in:'. implode(',', array_keys($userProfileFields['eye_color'])),
            'hair_color' => 'in:'. implode(',', array_keys($userProfileFields['hair_color'])),
            'drinking_habits' => 'in:'. implode(',', array_keys($userProfileFields['smoking_habits'])),
            'smoking_habits' => 'in:'. implode(',', array_keys($userProfileFields['drinking_habits'])),
            'about_me' => 'string|max:100',
            'looking_for' => 'string|max:100',
            'role' => 'in:'. implode(array_keys(\UserConstants::selectableField('role')))
        ];
    }
}
