<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name'  => 'required',
            'login' => 'required',
            'phone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'login.required'        => 'Логин обязателен',
            'name.required'         => 'Имя обязательно',
            'phone.required'        => 'Телефон обязателен',
        ];
    }
}
