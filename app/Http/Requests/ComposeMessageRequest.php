<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComposeMessageRequest extends BaseAPIRequest
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
            'to'    => 'required',
            'text'  => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'to.required'           => 'Номер телефона обязателен',
            'text.required'         => 'Текст сообщения обязателен',
        ];
    }
}
