<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

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
            'max_number' => 'required|numeric',
            'about' => 'max:2000',
            'post_photo' => 'max:10240',
            'post_photo.*' => 'file|mimes:jpg,jpeg,png,gif'
        ];
    }

    public function passwordHash()
    {
        if ($this->filled('post_password')) {
            $post_password = Hash::make($this->get('post_password'));
        } else {
            $post_password = $this->get('post_password');
        }
        return $post_password;
    }
}
