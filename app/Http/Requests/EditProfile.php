<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProfile extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:rs_users',
            'phone_number' => 'nullable|numeric|digits_between:10,15|unique:rs_users',
            'pin' => 'nullable|numeric|digits_between:4,6',
            'password' => 'nullable|string|min:6|confirmed'
        ];
    }
}

