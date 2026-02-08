<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneLocation extends FormRequest
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
            'phone_number' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string|max:255',
            'zip4' => 'nullable|string|max:255'
        ];
    }
}