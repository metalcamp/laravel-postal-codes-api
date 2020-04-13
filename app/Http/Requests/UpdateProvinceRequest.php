<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProvinceRequest extends FormRequest
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
        $province = $this->route('province');

        return [
            'name'       => "required|string|min:3|max:255|unique:provinces,name,{$province->id},id,deleted_at,NULL",
            'country_id' => 'required|int|exists:countries,id,deleted_at,NULL',
        ];
    }
}
