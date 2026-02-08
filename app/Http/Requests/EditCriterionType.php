<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCriterionType extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => "required|string|max:150|unique:rs_criterion_types,name,{$this->request->get('id')},id,deleted_at,NULL",
            'menu_order' => 'required|numeric|min:1',
        ];
    }
}