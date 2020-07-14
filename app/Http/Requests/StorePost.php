<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
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
            'post_title' => 'required|max:32',
            'min_number' => 'required|numeric',
            'max_number' => 'required|numeric|gte:min_number',
            'about' => 'max:2000'
        ];
    }
}
