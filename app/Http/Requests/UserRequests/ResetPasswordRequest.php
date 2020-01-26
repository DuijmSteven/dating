<?php

namespace App\Http\Requests\UserRequests;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class ResetPasswordRequest
 * @package App\Http\Requests\UserRequests
 */
class ResetPasswordRequest extends Request
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
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }
}
