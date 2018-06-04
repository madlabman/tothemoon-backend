<?php

namespace App\Http\Controllers\API;

use App\Fund;
use App\Http\Controllers\Controller;
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
                $balance = $user->balance;

                $commission_percent = 0.03;
                $can_take_from_bonus = bccomp($balance->bonus, $amount_tkn, 5) > 0;
                $can_take_from_body_and_bonus = bccomp(bcadd($balance->body, $balance->bonus, 5), $amount_tkn * (1 + $commission_percent), 5) > 0;
                $max_amount_usd = bcadd($balance->body, $balance->bonus, 5) * $fund->token_price / (1 + $commission_percent);
                $max_amount_btc = CryptoPrice::convert($max_amount_usd, 'usd', 'btc');

                if (!$can_take_from_bonus && !$can_take_from_body_and_bonus) {
                    return response()->json([
                        'status' => 'error',
                        'errors' => [
                            'amount' => [
                                'Недостаточно средств',
                                'Максимальная сумма для снятия ' . $max_amount_btc . ' BTC',
                            ],
                        ]
                    ]);
                }

                // Save withdraw
                $withdraw = Withdraw::create([
                    'amount'    => $request->post('amount'),
                    'wallet'    => $request->post('wallet'),
                ]);
                $user->withdraws()->save($withdraw);
                $user->save();

                // Take amount from balance
                if ($can_take_from_bonus) {
                    $user->balance->bonus = bcsub($user->balance->binus, $amount_tkn);
                } else {
                    $bonus_part = $user->balance->bonus;
                    $body_part = bcsub($amount_tkn * (1 + $commission_percent), $bonus_part, 5);

                    // TODO: commission to fund

                    $user->balance->bonus = 0;
                    $user->balance->body -= $body_part;
                    $user->balance->save();
                }

                return response()->json([
                    'status' => 'success',
                    'amount' => $amount_btc,
                ]);
            }
        } catch (\Exception $ex) {
            return response()->json([], 500);
        }

        return response()->json([], 500);
    }
}