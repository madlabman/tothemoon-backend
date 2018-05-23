<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class RegisterFormRequest extends BaseAPIRequest
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
            'login'  => 'required|string|unique:User',
            'password'  => 'required|string|min:6',
            'name'      => 'required',
            'phone'     => 'required|regex:/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/|unique:User',
            'email'     => 'required|email'
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
            //
            'login.unique'          => 'Логин занят',
            'phone.unique'          => 'Пользователь с таким номером уже зарегистрирован',
            //
            'login.required'        => 'Логин обязателен',
            'password.required'     => 'Пароль обязателен',
            'name.required'         => 'Имя обязательно',
            'phone.required'        => 'Телефон обязателен',
            'email.required'        => 'Почтовый адрес обязателен',
            //
            'password.min'          => 'Минимальная длина 6 символов',
            //
            'phone.regex'           => 'Введите номер телефона в формате +79001234567',
            //
            'email.email'           => 'Введите корректный адрес',
            'login.userunique'      => 'Введите цйвцц',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $errors
        ], 200));
    }
}
