<?php

namespace App\Http\Requests\Admin\Views;

use App\Http\Requests\Request;

/**
 * Class ViewUpdateRequest
 * @package App\Http\Requests\Admin\Articles
 */
class ViewUpdateRequest extends Request
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
            'name' => 'string|required|unique:views',
            'route_name' => 'string|without_spaces|required|unique:views',
        ];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->route_name;
    }
}
