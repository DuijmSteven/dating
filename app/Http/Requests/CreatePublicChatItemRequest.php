<?php

namespace App\Http\Requests;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

class CreatePublicChatItemRequest extends Request
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
            'text' => 'required|string|min:1|max:200',
            'operator_id' => 'integer',
            'type' => 'integer',
            'published_at' => 'date_format:d-m-Y H:i:s',
        ];

        return $rules;
    }
}
