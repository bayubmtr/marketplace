<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
            'name' => 'required',
            'id' => 'required',
            'city_id' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'province name',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name.unique' => ':attribute must be unique',
            'name.max' => ':attribute is max 50 character',
        ];
    }
}
