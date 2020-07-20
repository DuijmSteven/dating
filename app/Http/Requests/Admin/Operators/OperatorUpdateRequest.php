<?php

namespace App\Http\Requests\Admin\Operators;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Carbon\Carbon;

/**
 * Class OperatorUpdateRequest
 * @package App\Http\Requests\Admin\Operators
 */
class OperatorUpdateRequest extends Request
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
            'username' => 'min:5|max:50|string|required|unique:users,username,' . trim($this->route('id') . ',id'),
            'active' => 'boolean',
            'dob' => 'date_format:d-m-Y|before:' . Carbon::now('Europe/Amsterdam')->subYears(18)->format('d-m-Y') . '|after:' . Carbon::now('Europe/Amsterdam')->subYears(100)->format('d-m-Y'),
            'gender' => 'in:'. implode(',', array_keys($userProfileFields['gender'])),
            'city' => 'string|min:3|max:40',
            'email' => 'unique:users,email,' . trim($this->route('id') . ',id'),
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
