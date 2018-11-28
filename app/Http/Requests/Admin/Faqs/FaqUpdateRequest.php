<?php

namespace App\Http\Requests\Admin\Faqs;

use App\Http\Requests\Request;

/**
 * Class FaqUpdateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class FaqUpdateRequest extends Request
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
            'title' => 'string|required',
            'body' => 'required',
            'status' => 'integer|required'
        ];
    }
}
