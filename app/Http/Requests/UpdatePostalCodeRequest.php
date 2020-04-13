<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdatePostalCodeRequest extends FormRequest
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
        $postalCode = $this->route('postal_code');

        return [
            'code'    => "required|string|min:2|max:255|unique:postal_codes,code,{$postalCode->id},id,deleted_at,NULL",
            'city_id' => 'required|int|exists:cities,id,deleted_at,NULL',
        ];
    }
}
