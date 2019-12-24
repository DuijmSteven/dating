<?php

namespace App\Http\Requests\Admin\Peasants;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

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

        $rules = [
            'username' => 'min:5|max:50|string|required|unique:users,username,' . trim($this->route('userId') . ',id'),
            'active' => 'boolean',
            'dob' => 'date_format:Y-m-d',
            'gender' => 'in:'. implode(',', array_keys($userProfileFields['gender'])),
            'looking_for_gender' => 'in:'. implode(',', array_keys($userProfileFields['looking_for_gender'])),
            'relationship_status' => 'in:'. implode(',', array_keys($userProfileFields['relationship_status'])),
            'body_type' => 'in:'. implode(',', array_keys($userProfileFields['body_type'])),
            'eye_color' => 'in:'. implode(',', array_keys($userProfileFields['eye_color'])),
            'height' => 'in:'. implode(',', array_keys($userProfileFields['height'])),
            'hair_color' => 'in:'. implode(',', array_keys($userProfileFields['hair_color'])),
            'smoking_habits' => 'in:'. implode(',', array_keys($userProfileFields['drinking_habits'])),
            'drinking_habits' => 'in:'. implode(',', array_keys($userProfileFields['smoking_habits'])),
            'city' => 'string|min:3|max:40',
            'lat' => 'required_with:city|numeric',
            'lng' => 'required_with:city|numeric',
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
