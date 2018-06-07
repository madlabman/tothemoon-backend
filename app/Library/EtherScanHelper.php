<?php

namespace App\Library;

use GuzzleHttp\Exception\GuzzleException;

class EtherScanHelper
{
    public static function get_wallet_balance(string $wallet)
    {
        if (empty($api_key = config('app.etherscan_key'))) return null;

        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://api.etherscan.io/api', [
                'query' => [
                    'module'    => 'account',
                    'action'    => 'balance',
                    'address'   => $wallet,
                    'tag'       => 'latest',
                    'apikey'    => $api_key,
                ]
            ]);
            $status = $res->getStatusCode();
            if ($status == 200) {
                $data = json_decode($res->getBody(), true);
                if (!empty($data['status']) && $data['status'] === '1') {
                    return $data['result'] / 1000000000000000000.0;
                }
            }
        } catch (GuzzleException $ex) {
            //
        }

        return null;
    }
}