<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositCreateRequest extends BaseAPIRequest
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
            'duration'  => 'required|numeric',
            'wallet'    => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'amount.required'   => 'Сумма вклада обязательно',
            'amount.numeric'   => 'Неверный формат суммы',
            'duration.required' => 'Длительность вклада обязательна',
            'duration.numeric' => 'Неверный формат длительности',
            'wallet.required'   => 'Не указан кошелек для проверки оплаты',
        ];
    }
}
