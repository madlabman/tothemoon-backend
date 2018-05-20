<?php

namespace App\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BittrexHelper
{
    public static function get_balances()
    {
        try {
            $api_key = config('app.bittrex_key');
            $api_secret = config('app.bittrex_secret');
            if (empty($api_key) || empty($api_secret)) return null;

            $query_params = [
                'apikey'    => $api_key,
                'nonce'     => time(),
            ];
            $uri = 'https://bittrex.com/api/v1.1/account/getbalances?' . http_build_query($query_params);
            $sign = hash_hmac('sha512', $uri, $api_secret);

            $client = new Client();
            $res = $client->request('GET', $uri, [
                'headers' => [
                    'apisign'   => $sign,
                ],
            ]);

            if ($res->getStatusCode() == 200) {
                return json_decode($res->getBody(), true);
            }
        } catch (GuzzleException $guzzleException) {
            //
        }

        return null;
    }

    public static function convert_to_btc($balance, $currency)
    {
        try {
            $uri = 'https://bittrex.com/api/v1.1/public/getticker?' . http_build_query([
                'market' => 'BTC-' . $currency,
            ]);

            $client = new Client();
            $res = $client->request('GET', $uri);
            if ($res->getStatusCode() == 200) {
                $ticker = json_decode($res->getBody(), true);
                if ($ticker['success']) {
                    $convert = $ticker['result']['Last'];
                    return $balance * $convert;
                }
            }
        } catch (GuzzleException $guzzleException) {
            //
        }

        return null;
    }
}