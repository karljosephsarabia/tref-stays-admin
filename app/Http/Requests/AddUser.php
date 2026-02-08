<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;

class AddUser extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:rs_users',
            'email' => 'nullable|string|email|max:255|unique:rs_users', //Removed required option
            'role_id' => 'required|in:' . join(',', RoleType::ALL),
            'phone_number' => 'required|numeric|digits_between:10,15|unique:rs_users',
            'pin' => 'required|numeric|digits_between:4,6',
            'password' => 'required_with:email|nullable|string|min:6|confirmed'
        ];


        if (request()->input('role_id') == RoleType::OWNER) {
            $rules['commission'] = 'required|numeric|not_in:0';
            $rules['payment_via'] = 'required|in:' . join(',', RsPaymentVia::TYPES);
        }

        return $rules;
    }
}
