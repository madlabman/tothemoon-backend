<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentCreateRequest;
use App\Payment;

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
                            'created_at'   => $item->created_at->addHours(4)->format('d-m-Y H:i:s'),
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