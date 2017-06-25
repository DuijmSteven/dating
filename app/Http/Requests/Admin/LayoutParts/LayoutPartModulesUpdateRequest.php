<?php

namespace App\Http\Requests\Admin\LayoutParts;

use App\Http\Requests\Request;

/**
 * Class LayoutPartModulesUpdateRequest
 * @package App\Http\Requests\Admin\Modules
 */
class LayoutPartModulesUpdateRequest extends Request
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
            'modules.*.priority' => 'int|min:1',
            'modules.*.active' => 'in:on',
        ];
    }
}
