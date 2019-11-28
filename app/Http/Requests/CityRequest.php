<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'name' => 'required|max:50',
            'id' => 'required|integer',
            'province_id' => 'required|integer|exists:address_provinces,id',
            'type' => 'in:Kota,Kabupaten'
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'city name',
            'id' => 'city id',
            'province_id' => 'province id',
            'type' => 'city type'

        ];
    }
    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name.max' => ':attribute is max 50 character',
            'id.integer' => ':attribute must be numeric',
            'id.required' => ':attribute is required',
            'province_id.required' => ':attribute is required',
            'province_id.integer' => ':attribute must be numeric',
            'province_id.exists' => ':attribute must be exist in province',
            'type.in' => ':attribute must be Kabupaten or Kota'
        ];
    }
}
