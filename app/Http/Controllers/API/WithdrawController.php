<?php

namespace App\Http\Controllers\API;

use App\Exceptions\NotEnoughMoneyToWithdraw;
use App\Fund;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WithdrawController as BaseController;
use App\Http\Requests\PaymentCreateRequest;
use App\Library\CryptoPrice;
use App\Withdraw;

class WithdrawController extends Controller
{
    public function create(PaymentCreateRequest $request)
    {
        try {
            if (!(empty($user = auth()->user()))) {
                $fund = Fund::where('slug', 'tothemoon')->first();
                if (empty($fund)) throw new \Exception("Fund doesn't exist.");
                // Getting amount
                $amount_btc = $request->post('amount');
                $amount_usd = CryptoPrice::convert($amount_btc, 'btc', 'usd');
                $amount_tkn = $amount_usd / $fund->token_price;

                $transaction = BaseController::try_to_withdraw($user, $amount_tkn);

                // Save withdraw
                $withdraw = Withdraw::create([
                    'amount'    => $request->post('amount'),
                    'wallet'    => $request->post('wallet'),
                ]);
                $withdraw->transaction()->save($transaction);
                $user->withdraws()->save($withdraw);
                $user->save();


                return response()->json([
                    'status' => 'success',
                    'amount' => $amount_btc,
                ]);
            }
        }
        catch (NotEnoughMoneyToWithdraw $ex) {
            return response()->json([
                'status' => 'error',
                'errors' => [
                    'amount' => [
                        'Недостаточно средств',
                        'Максимальная сумма для снятия ' . $ex->getMaxBtc() . ' BTC',
                    ],
                ]
            ]);
        }
        catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }
}