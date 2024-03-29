<?php

namespace App\Http\Requests\Admin\Conversations;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

class MessageCreateRequest extends Request
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
    public static function rules()
    {
        $rules = [
            'sender_id' => 'integer',
            'recipient_id' => 'integer',
            'message' => 'required_without:attachment|string|min:1|max:1000',
            'attachment' => 'image|max:15000',
        ];

        return $rules;
    }
}
