<?php

namespace App\Http\Controllers\API;

use App\Events\PaymentConfirmed;
use App\Fund;
use App\Http\Controllers\Controller;
use App\Http\Requests\BitApsCallbackRequest;
use App\Library\BitApsHelper;
use App\Library\CryptoPrice;
use App\Payment;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Event;

class PaymentController extends Controller
{
    public function create()
    {
        try {
            if (!(empty($user = auth()->user()))) {
                // Check payment address
                $payment_address = $user->payment_address;
                if (empty($payment_address)) {
                    // Create new payment address
                    $bitApsResponse = BitApsHelper::create_payment_address($user);
                    if (!$bitApsResponse->isValid()) throw new \Exception('Invalid BitApsResponse');
                    $payment_address = $bitApsResponse->getAddress();
                    $user->payment_address = $payment_address;
                    $user->payment_code = $bitApsResponse->getPaymentCode();
                    $user->save();
                }

                // Return success
                return response()->json([
                    'status'  => 'success',
                    'address' => $payment_address
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }

    public function receive(BitApsCallbackRequest $request, $user_uuid, Fund $fund)
    {
        $user = User::where('uuid', $user_uuid)->first();
        if (empty($user)) return;

        // It seems I should to compare payment code with stored in database
        if (true) {
            $payment = Payment::create([
                'amount'    => $request->amount / 100000000,    // Convert from Satoshi,
                'wallet'    => $request->address,
                'type'      => Payment::BTC,
                'tx_hash'   => $request->tx_hash,
                'confirmed' => true
            ]);

            $user->payments()->save($payment);
            $user->save();

            // Add transaction
            $usd_eq = CryptoPrice::convert($request->post('amount'), 'btc', 'usd');
            $token_count = $usd_eq / $fund->token_price;
            $transaction = Transaction::create([
                'type'        => Transaction::PAYMENT,
                'token_count' => $token_count,
                'token_price' => $fund->token_price,
            ]);
            $transaction->user()->associate($user)->save();

            // Return response to the BitAps
            echo $request->invoice;
        }
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