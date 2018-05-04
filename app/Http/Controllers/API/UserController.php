<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Library\CryptoPrice;
use App\User;

class UserController extends Controller
{
    /**
     * Return list of all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function users()
    {
        try {
            return response()->json([
                'users' => User::all()
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * Return balance of current user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_balance()
    {
        try {
            if (!empty(auth()->user()->balance)) {
                $btc = auth()->user()->balance->body;
                $usd = CryptoPrice::convert($btc, 'btc', 'usd');
                $rub = CryptoPrice::convert($btc, 'btc', 'rub');

                return response()->json([
                    'status'    => 'success',
                    'balance'   => [
                        'btc'   => $btc,
                        'usd'   => $usd,
                        'rub'   => $rub,
                    ]
                ]);
            } else {
                throwException(new \Exception('Balance not found.'));
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
                'balance' => null,
                'error' => $ex->getMessage(),
            ], 500);
        } finally {
            return response()->json([], 500);
        }
    }

    /**
     * Return basic info about user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => auth()->user(),
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }
}