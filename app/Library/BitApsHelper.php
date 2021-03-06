<?php

namespace App\Library;

use App\User;
use GuzzleHttp\Exception\GuzzleException;

class BitApsHelper
{
    /**
     * @var string Uri for API requests
     */
    private static $API_BASE = 'https://bitaps.com';

    /**
     * @param User $user
     * @return string
     */
    private static function build_callback_uri(User $user): string
    {
        return urlencode(config('app.api_url') . '/api/v1/payment/receive/' . $user->uuid);
    }

    /**
     * @param User $user
     * @return string
     */
    private static function build_create_payment_address_uri(User $user): string
    {
        return self::$API_BASE . '/api/create/payment/'
            . urlencode(config('app.BTC_ADDRESS')) . '/'
            . self::build_callback_uri($user);
    }

    /**
     * @param User $user
     * @return BitApsResponse
     */
    public static function create_payment_address(User $user): BitApsResponse
    {
        $bitapsResponse = new BitApsResponse();
        // Skip if user has not uuid
        if (empty($user) || empty($user->uuid)) return $bitapsResponse;

        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', self::build_create_payment_address_uri($user));
            $status = $res->getStatusCode();
            if ($status === 200) {
                $response = json_decode($res->getBody());
                // Validate
                if (
                    !empty($response)
                    && !empty($response->address)
                    && !empty($response->invoice)
                    && !empty($response->payment_code)
                ) {
                    $bitapsResponse->setAddress($response->address);
                    $bitapsResponse->setInvoice($response->invoice);
                    $bitapsResponse->setPaymentCode($response->payment_code);
                    $bitapsResponse->setIsValid(true);
                }
            }
        } catch (GuzzleException $ex) {
            //
        }

        return $bitapsResponse;
    }
}