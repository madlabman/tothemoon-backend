<?php

namespace App\Http\Controllers\API;

use App\Fund;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentCreateRequest;
use App\Library\CryptoPrice;
use App\Payment;
use App\Transaction;

class PaymentController extends Controller
{
    public function create(PaymentCreateRequest $request)
    {
        try {
            if (!(empty($user = auth()->user()))) {
                $payment = Payment::create([
                    'amount' => $request->post('amount'),
                    'wallet' => $request->post('wallet'),
                    'type'   => Payment::BTC,
                ]);

                $user->payments()->save($payment);
                $user->save();

                // Add transaction
                $fund = Fund::where('slug', 'tothemoon')->first();
                $usd_eq = CryptoPrice::convert($request->post('amount'), 'btc', 'usd');
                $transaction = Transaction::create([
                    'type'        => Transaction::PAYMENT,
                    'token_count' => $usd_eq / $fund->token_price,
                    'token_price' => $fund->token_price
                ]);
                $transaction->user()->associate($user)->save();

                // Return success
                return response()->json([
                    'status'  => 'success',
                    'address' => config('app.BTC_ADDRESS')
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }

    public function history()
    {
        try {
            if (!(empty($user = auth()->user()))) {
                $payments = $user->payments()->limit(15)->get();
                return response()->json([
                    'status'   => 'success',
                    'payments' => $payments->map(function ($item) {
                        return [
                            'is_confirmed' => $item->is_confirmed,
                            'created_at'   => $item->created_at->timezone(config('app.TZ'))->format('d-m-Y H:i:s'),
                            'amount'       => $item->amount,
                            'link'         => $item->tx_hash ? "https://www.blockchain.com/ru/btc/tx/$item->tx_hash" : false,
                        ];
                    })
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }
}