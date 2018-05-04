<?php

namespace App\Library;

class CryptoPrice
{
    public static function convert(float $amount, string $from, string $to): float
    {
        if ($amount == 0) return 0;

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
        return 9000.0; // TODO: implement cache and retrieve real value
    }

    /**
     * @return null|float
     */
    public static function btc_rub(): ?float
    {
        return 580000.0; // TODO: implement cache and retrieve real value
    }
}