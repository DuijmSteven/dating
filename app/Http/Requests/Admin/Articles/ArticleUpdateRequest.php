<?php

namespace App\Http\Requests\Admin\Articles;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class ArticleUpdateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class ArticleUpdateRequest extends Request
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
