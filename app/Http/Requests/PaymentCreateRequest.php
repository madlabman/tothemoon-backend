<?php

namespace App\Http\Requests;

class PaymentCreateRequest extends BaseAPIRequest
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
            'amount'    => 'required|numeric',
            'wallet'    => 'required|string'
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
            'amount.required'   => 'Необходимо указать количество',
            'amount.numeric'    => 'Введите корректное число',
            //
            'wallet.required'   => 'Необходимо указать кошелек',
            'wallet.size'       => 'Введите корректный адрес'
        ];
    }
}
