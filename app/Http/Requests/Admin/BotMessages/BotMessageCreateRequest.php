<?php

namespace App\Http\Requests\Admin\BotMessages;

use App\Http\Requests\Request;

class BotMessageCreateRequest extends Request
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
            'body' => 'required|min:1|max:1000',
            'bot_id' => 'integer',
            'status' => 'integer|required',
            'usage_type' => 'integer|required'
        ];
    }
}
