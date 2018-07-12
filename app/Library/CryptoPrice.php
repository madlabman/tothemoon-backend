<?php

namespace App\Library;

use App\Fund;
use Cache;
use GuzzleHttp\Exception\GuzzleException;

class CryptoPrice
{
    private static function update()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://blockchain.info/ru/ticker');
            $status = $res->getStatusCode();
            if ($status == 200) {
                $ticker = $res->getBody();
                $ticker = json_decode($ticker, true);
                foreach ($ticker as $symbol => $tick) {
                    Cache::add(mb_strtolower($symbol), $tick['last'], 10);
                }
                Cache::add('btc', 1 / $ticker['USD']['last'], 10);
            }
        } catch (GuzzleException $ex) {
            //
        }
    }

    public static function convert(float $amount, string $from, string $to): float
    {
        if ($amount == 0) return 0;

        if (!Cache::has($to)) {
            self::update();
        }

        $method_name = $from . '_' . $to;
        if (method_exists(self::class, $method_name)) {
            return $amount * self::$method_name();
        } else {
            return 0;
        }
    }

    /**
     * Return array of price in btc, usd and rub.
     *
     * @param float $token_amount
     */
    public static function get_base_currencies_from_token(float $token_amount)
    {
        $fund = Fund::where('slug', 'tothemoon')->first();
        $usd = round($token_amount * $fund->token_price, 2);
        $btc = round(CryptoPrice::convert($usd, 'usd', 'btc'), 5);
        $rub = round(CryptoPrice::convert($btc, 'btc', 'rub'), 2);

        return [
            'btc'   => $btc,
            'usd'   => $usd,
            'rub'   => $rub,
        ];
    }

    /**
     * @return null|float
     */
    public static function btc_usd(): ?float
    {
        return Cache::get('usd');
    }

    /**
     * @return null|float
     */
    public static function btc_rub(): ?float
    {
        return Cache::get('rub');
    }

    public static function usd_btc()
    {
        return Cache::get('btc');
    }
}