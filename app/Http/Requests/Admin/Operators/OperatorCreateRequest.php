<?php

namespace App\Http\Requests\Admin\Operators;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Carbon\Carbon;

/**
 * Class OperatorCreateRequest
 * @package App\Http\Requests\Admin\Operators
 */
class OperatorCreateRequest extends Request
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
        $userProfileFields = UserConstants::selectableFields('common');

        $rules = [
            'username' => 'min:5|max:50|string|required|unique:users',
            'password' => 'required|min:8',
            'active' => 'required|boolean',
            'dob' => 'required|date_format:d-m-Y|before:' . Carbon::now('Europe/Amsterdam')->subYears(18)->format('d-m-Y') . '|after:' . Carbon::now('Europe/Amsterdam')->subYears(100)->format('d-m-Y'),
            'gender' => 'required|in:'. implode(',', array_keys($userProfileFields['gender'])),
            'city' => 'string|min:3|max:40',
            'profile_image' => 'image|max:4000',
        ];

        return $rules;
    }
}
