<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostalCodeRequest extends FormRequest
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
        //TODO must be unique postal code in country
        return [
//            'name'        => 'required|string|min:3|max:255|unique:cities,name,NULL,id,deleted_at,NULL',

            'code'        => 'required|string|min:2|max:255|unique:postal_codes,code,NULL,id,deleted_at,NULL',
//            'code'        => 'required|string|min:2|max:255|unique:postal_codes,code,NULL,deleted_at,NULL',
            'city_id'     => 'required|int|exists:cities,id,deleted_at,NULL',
        ];
    }
}
