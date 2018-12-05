<?php

namespace App\Http\Requests\Admin\Testimonials;

use App\Helpers\ApplicationConstants\UserConstants;
use App\Http\Requests\Request;

/**
 * Class ArticleUpdateRequest
 * @package App\Http\Requests\Admin\Bots
 */
class TestimonialUpdateRequest extends Request
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
            'title' => 'string',
            'body' => 'string|required',
            'status' => 'integer|required',
            'pretend_at' => 'date_format:d-m-Y',
            'user_a_id' => 'integer',
            'user_b_id' => 'integer'
        ];
    }
}
