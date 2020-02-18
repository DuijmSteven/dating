<?php

namespace App\Http\Requests;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;
use Illuminate\Support\Carbon;

/**
 * Class RegisterRequest
 * @package App\Http\Requests
 */
class RegisterRequest extends Request
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
            'username' => 'required|max:50|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            //'city' => 'required|in:' . implode(',', UserConstants::$cities['nl']),
            //'dob' => 'date_format:d-m-Y|required|before:' . Carbon::now()->subYears(18)->format('d-m-Y') . '|after:' . Carbon::now()->subYears(100)->format('d-m-Y'),
        ];
    }
}
