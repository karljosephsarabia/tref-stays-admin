<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SMD\Common\Stripe\Enums\SourceType;

class EditSource extends FormRequest
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
        if (request()->has('source')) {
            $source = request()->input('source');

            if ($source == SourceType::CARD) {
                return [
                    'exp_month' => 'required|numeric|max:12|min:1|digits_between:1,2',
                    'exp_year' => 'required|numeric|digits_between:4,4',
                ];
            }

            if ($source == SourceType::BANK_ACCOUNT) {
                return [
                    'account_holder_name' => 'required|string',
                ];
            }
        }

        return [];
    }
}