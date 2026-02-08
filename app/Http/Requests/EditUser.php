<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;

class EditUser extends FormRequest
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
            'role_id' => 'required|in:' . join(',', RoleType::ALL),
            'pin' => 'nullable|numeric|digits_between:4,6',
            'password' => 'nullable|string|min:6|confirmed'
        ];

        if (request()->input('role_id') == RoleType::OWNER) {
            $rules['commission'] = 'required|numeric|not_in:0';
            $rules['payment_via'] = 'required|in:' . join(',', RsPaymentVia::TYPES);
        }

        return $rules;
    }
}
