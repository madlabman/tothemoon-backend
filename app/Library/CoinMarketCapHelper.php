<?php
/**
 * Created by PhpStorm.
 * User: che
 * Date: 05.06.2018
 * Time: 8:35
 */

namespace App\Library;


use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class CoinMarketCapHelper
{
    private static function update()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/listings/');
            $status = $res->getStatusCode();
            if ($status == 200) {
                $ticker = $res->getBody();
                $ticker = json_decode($ticker, true);
                foreach ($ticker['data'] as $symbol) {
                    Cache::add(mb_strtolower($symbol['symbol']), self::get_price_by_id($symbol['id']), 25);
                }
            }
        } catch (GuzzleException $ex) {
            //
        }
    }

    private static function get_price_by_id($id)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/ticker/' . $id);
            $status = $res->getStatusCode();
            if ($status == 200) {
                $ticker = $res->getBody();
                $ticker = json_decode($ticker, true);

                if (!empty($ticker['data']))
                    return $ticker['data']['quotes']['USD'];
            }
        } catch (GuzzleException $ex) {
            //
        }

        return 0;
    }

    public static function price($symbol)
    {
        if (!Cache::has($symbol)) {
            self::update();
        }

        return Cache::get($symbol, 0);
    }

}