<?php
/**
 * Created by PhpStorm.
 * User: che
 * Date: 05.06.2018
 * Time: 8:35
 */

namespace App\Library;


use App\CryptoCurrency;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class CoinMarketCapHelper
{

    private static function get_price($symbol)
    {
        try {
            $coin = CryptoCurrency::where('symbol', $symbol)->first();
            if (!empty($coin)) {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/ticker/' . $coin->market_id);
                $status = $res->getStatusCode();
                if ($status == 200) {
                    $ticker = $res->getBody();
                    $ticker = json_decode($ticker, true);

                    if (!empty($ticker['data'])) {
                        $price = $ticker['data']['quotes']['USD']['price'];
                        // Save to cache
                        Cache::add($symbol, $price, 30);
                        $coin->stored_price = $price;
                        $coin->save();

                        return $price;
                    }
                }
            }
        } catch (GuzzleException $ex) {
            echo $ex->getMessage();
            return $coin->stored_price;
        }

        return 0;
    }

    public static function price($symbol)
    {
        if (!Cache::has($symbol)) {
            return self::get_price($symbol);
        }

        return Cache::get($symbol, 0);
    }

}