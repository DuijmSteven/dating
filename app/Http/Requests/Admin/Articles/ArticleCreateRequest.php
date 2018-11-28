<?php

namespace App\Http\Requests\Admin\Faqs;

use App\Http\Requests\Request;

/**
 * Class ArticleCreateRequest
 * @package App\Http\Requests\Admin\Articles
 */
class ArticleCreateRequest extends Request
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
            'section' => 'string|required',
            'title' => 'string|required',
            'body' => 'required',
            'status' => 'integer|required'
        ];
    }
}
