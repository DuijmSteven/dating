<?php

namespace App\Http\Requests\Admin\Expenses;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class ExpenseUpdateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class ExpenseUpdateRequest extends Request
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
            'payee' => 'integer|required',
            'type' => 'integer|required',
            'description' => 'string|max:255',
            'amount' => 'regex:/^\d+(\.\d{1,2})?$/|required',
            'takes_place_at' => 'date_format:d-m-Y',
        ];
    }
}
