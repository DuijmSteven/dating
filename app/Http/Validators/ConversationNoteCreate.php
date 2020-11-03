<?php

namespace App\Http\Validators;

use App\Http\Requests\Request;

class ConversationNoteCreate extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
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
