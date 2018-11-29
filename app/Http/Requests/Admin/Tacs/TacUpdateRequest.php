<?php

namespace App\Http\Requests\Admin\Tacs;

use App\Http\Requests\Request;

/**
 * Class TacUpdateRequest
 * @package App\Http\Requests\Admin\Tacs
 */
class TacUpdateRequest extends Request
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
            'content' => 'string|required'
        ];
    }
}
