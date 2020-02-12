<?php

namespace App\Http\Requests\Admin\Conversations;

use App\Http\Requests\Request;

class AddInvisibleImageToConversationRequest extends Request
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
            'sender_id' => 'required|integer',
            'recipient_id' => 'required|integer',
            'conversation_id' => 'required|integer',
            'image_id' => 'required|integer',
        ];

        return $rules;
    }
}