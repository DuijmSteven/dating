<?php

namespace App\Http\Requests\Admin\LayoutParts;

use App\Http\Requests\Request;

/**
 * Class ModuleInstancesUpdateRequest
 * @package App\Http\Requests\Admin\Modules
 */
class ModuleInstancesUpdateRequest extends Request
{
    /**
     * Constructor.
     *
     * @param array           $query      The GET parameters
     * @param array           $request    The POST parameters
     * @param array           $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array           $cookies    The COOKIE parameters
     * @param array           $files      The FILES parameters
     * @param array           $server     The SERVER parameters
     * @param string|resource $content    The raw body data
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'views.*.*.*.priority' => 'required_with:views.*.*.*.active|int|min:1',
            'views.*.*.*.active' => 'in:on',
        ];
    }
}
