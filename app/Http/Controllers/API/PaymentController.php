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
                    'amount'    => $request->post('amount'),
                    'wallet'    => $request->post('wallet'),
                    'type'      => Payment::BTC,
                ]);

                $user->payments()->save($payment);
                $user->save();

                return response()->json([
                    'status' => 'success',
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }
}