<?php

namespace App\Http\Requests\Admin\LayoutParts;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class LayoutPartUpdateRequest
 * @package App\Http\Requests\Admin\Modules
 */
class LayoutPartUpdateRequest extends Request
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
            'name' => 'string|required'
        ];
    }
}
