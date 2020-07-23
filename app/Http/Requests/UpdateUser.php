<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'login_id' => ['string', 'min:8','max:12','alpha_num','unique:users'],
            'name' => ['string', 'max:255'],
            'user_image' => ['file', 'mimes:jpg,jpeg,png,gif'],
        ];
    }
}
