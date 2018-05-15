<?php

namespace App\Http\Controllers\API;

use App\Balance;
use App\Deposit;
use App\DepositPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositCreateRequest;

class DepositController extends Controller
{
    public function create(DepositCreateRequest $request)
    {
        try {
            $deposit = Deposit::create([
                'initial_amount'    => $request->amount,
                'duration'          => $request->duration,
            ]);

            $balance = new Balance();
            $balance->save();

            $depositPayment = DepositPayment::create([
                'amount'            => $request->amount,
                'wallet'            => $request->wallet,
            ]);

            $depositPayment->deposit()->associate($deposit)->save();
            $deposit->user()->associate(auth()->user())->save();
            $deposit->balance()->save($balance);

            return response()->json([
                'status'    => 'success',
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function read($id)
    {
        try {
            $deposit = Deposit::find($id);
            if (!empty($deposit)) {
                return response()->json([
                    'status' => 'success',
                    'deposit' => $deposit,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function delete($id)
    {
        try {
            $deposit = Deposit::find($id);
            if (!empty($deposit)) {
                if ($deposit->user->uuid !== auth()->user()->uuid) {
                    return response()->json([
                        'status'    => 'error',
                    ], 401);
                }
                if (!empty($deposit->balance)) $deposit->balance->delete();
                foreach ($deposit->payments as $payment) {
                    $payment->delete();
                }
                $deposit->delete();
            }

            return response()->json([
                'status'    => 'success',
            ]);
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }
    }

    public function close($id)
    {
        //
    }
}