<?php

namespace App\Library;

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
}