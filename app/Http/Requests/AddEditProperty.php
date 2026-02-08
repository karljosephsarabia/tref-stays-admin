<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\PropertyType;

class AddEditProperty extends FormRequest
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
            'owner_id' => 'required|exists:rs_users,id',
            'title' => 'required|string|max:150',
            'property_type' => 'required|in:' . join(',', PropertyType::TYPES),
            'price' => 'required|numeric|min:1',
            'guest_count' => 'required|numeric|min:1',
            'bed_count' => 'required|numeric|min:1',
            'bedroom_count' => 'required|numeric|min:1',
            'bathroom_count' => 'required|numeric|min:1',
            'cancellation_type' => 'required|in:' . join(',', CancellationType::TYPES),
            'cancellation_cut' => 'numeric|required_if:cancellation_type,' . CancellationType::PARTIAL,
            'zipcode_id' => 'required',
            'street_name' => 'required|string|max:150',
            'house_number' => 'required|string|max:150',
            'additional_luxury' => 'nullable|string',
            'additional_information' => 'nullable|string'
        ];
    }
}
