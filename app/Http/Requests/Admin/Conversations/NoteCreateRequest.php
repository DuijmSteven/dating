<?php

namespace App\Http\Requests\Admin\Conversations;

use App\Http\Requests\Request;

class NoteCreateRequest extends Request
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
        $rules = [
            'user_id' => 'required|integer',
            'conversation_id' => 'required|integer',
            'category_id' => 'required|integer',
            'body' => 'required|string|max:255'
        ];

        return $rules;
    }
}
