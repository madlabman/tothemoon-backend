<?php

namespace App\Library;

use App\Payment;
use GuzzleHttp\Exception\GuzzleException;

class BlockchainHelper
{
    public function __construct()
    {
        // Init api
        // https://blockchain.info/ru/api/api_receive
    }

    public function receive_balance_update(Payment $payment)
    {
        // Create request
    }

    public static function get_transactions(string $address): ?array
    {
        // https://blockchain.info/ru/rawaddr/$bitcoin_address
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://blockchain.info/ru/rawaddr/' . $address);
            $status = $res->getStatusCode();
            if ($status == 200) {
                return json_decode($res->getBody(), true);
            }
        } catch (GuzzleException $ex) {
            //
        }

        return null;
    }
}