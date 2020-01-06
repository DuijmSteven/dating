<?php

namespace App\Http\Requests;

/**
 * Class ContactRequest
 * @package App\Http\Requests\Admin\Articles
 */
class ContactRequest extends Request
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
            'email' => 'email|required',
            'name' => 'string|required|max:100',
            'subject' => 'string|required|max:200',
            'body' => 'string|required|max:2000',
        ];
    }
}
