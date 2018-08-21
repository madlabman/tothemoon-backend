<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BitApsCallbackRequest extends BaseAPIRequest
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
            'tx_hash'            => 'required',
            'address'            => 'required',
            'invoice'            => 'required',
            'code'               => 'required',
            'amount'             => 'required',
            'confirmations'      => 'required',
            'payout_tx_hash'     => 'required',
            'payout_miner_fee'   => 'required',
            'payout_service_fee' => 'required'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator)
    {
        (new ValidationException($validator))->errors();
        throw new HttpResponseException(response('INVALID_REQUEST', 400));
    }
}
