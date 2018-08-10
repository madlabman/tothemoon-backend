<?php

namespace App\Http\Requests;

use App\Library\CryptoPrice;

class PaymentCreateRequest extends BaseAPIRequest
{
    /**
     * @var float|int Minimum accepted amount value in BTC
     */
    protected $min_btc = 0;

    /**
     * @var int Minimum accepted amount value in USD
     */
    protected $min_usd = 500;

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        // Calculate min price
        $this->min_btc = CryptoPrice::convert($this->min_usd, 'usd', 'btc');
    }

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
            'amount' => 'required|numeric|min:' . $this->min_btc,
            'wallet' => 'required|string'
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
            'amount.required' => 'Необходимо указать количество',
            'amount.numeric'  => 'Введите корректное число',
            'amount.min'      => 'Минимальная сумма ' . round($this->min_btc, 5) . 'BTC',
            //
            'wallet.required' => 'Необходимо указать кошелек',
            'wallet.size'     => 'Введите корректный адрес'
        ];
    }
}
