<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentCreateRequest;
use App\Withdraw;

class WithdrawController extends Controller
{
    public function create(PaymentCreateRequest $request)
    {
        try {
            if (!(empty($user = auth()->user()))) {
                $amount = $request->post('amount');
                $balance = $user->balance->body;

                if (bccomp($balance, $amount, 5) < 0 ) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => [
                            'amount' => [
                                'Недостаточно средств'
                            ],
                        ]
                    ]);
                }

                $payment = Withdraw::create([
                    'amount'    => $request->post('amount'),
                    'wallet'    => $request->post('wallet'),
                ]);

                $user->withdraws()->save($payment);
                $user->balance->body = bcsub($balance, $amount, 5);

                $user->balance->save();
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'amount' => $amount,
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }
}