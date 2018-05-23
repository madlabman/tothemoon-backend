<?php

namespace App\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinanceHelper
{
    public static function get_balances()
    {
        try {
            $api_key = config('app.binance_key');
            $api_secret = config('app.binance_secret');
            if (empty($api_key) || empty($api_secret)) return null;

            $query_params = [
                'timestamp' => round(microtime(true) * 1000),
                'recvWindow' => 36000000,
            ];
            $sign = hash_hmac('sha256', http_build_query($query_params), $api_secret);
            $query_params['signature'] = $sign;
            $uri = 'https://api.binance.com/api/v3/account?' . http_build_query($query_params);

            $client = new Client();
            $res = $client->request('GET', $uri, [
                'headers' => [
                    'X-MBX-APIKEY' => $api_key,
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
            $uri = 'https://api.binance.com/api/v1/ticker/24hr?' . http_build_query([
                'symbol' => $currency . 'BTC',
            ]);

            $client = new Client();
            $res = $client->request('GET', $uri);
            if ($res->getStatusCode() == 200) {
                $ticker = json_decode($res->getBody(), true);
                $convert = $ticker['lastPrice'];
                return $balance * $convert;
            }
        } catch (GuzzleException $guzzleException) {
            //
        }

        return null;
    }
}