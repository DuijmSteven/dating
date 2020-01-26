<?php

namespace App\Http\Requests\Admin\Peasants;

use App\Http\Requests\Request;

class PeasantUpdateImagesRequest extends Request
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
            'profile_image' => 'required_without:user_images|image|max:4000',
        ];

        if (!is_null($this->files->get('user_images'))) {
            $imageCount = count($this->files->get('user_images')) - 1;
            foreach (range(0, $imageCount) as $index) {
                $rules['required_without:profile_image|user_images.' . $index] = 'image|max:4000';
            }
        }
        return $rules;
    }
}
