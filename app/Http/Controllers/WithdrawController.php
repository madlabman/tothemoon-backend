<?php

namespace App\Http\Controllers;

use App\Exceptions\NotEnoughMoneyToWithdraw;
use App\Fund;
use App\Library\CryptoPrice;
use App\Mail\WithdrawConfirmed;
use App\Transaction;
use App\User;
use App\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WithdrawController extends Controller
{
    protected const PER_PAGE = 12;

    protected const COMMISSION = 0.03;

    protected const MONTHS_WITH_FEE = 6;

    protected const FEE_PERCENT = 0.25;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $withdraws = Withdraw::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = Withdraw::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/withdraws/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('withdraws.all')->with([
            'withdraws'     => $withdraws,
            'pages'         => $pages,
        ]);
    }

    public function delete($id)
    {
        $withdraw = Withdraw::find($id);
        try {
            self::cancel_withdraw($withdraw);
            $withdraw->delete();
            \request()->session()->flash('status', 'Выплата удалена, средства возвращены!');
        } catch (\Exception $exception) {
            \request()->session()->flash('status', 'Произошла ошибка, обратитесь к администратору!');
        }

        return redirect('withdraws');
    }

    public function confirm($id)
    {
        $withdraw = Withdraw::find($id);
        if (!empty($withdraw)) {
            $withdraw->is_confirmed = true;
            $withdraw->save();
            \request()->session()->flash('status', 'Выплата подтверждена!');
            // Send email
            try {
                Mail::to(config('app.admin_email'))
                    ->cc(config('app.admin_email_alt'))
                    ->send(new WithdrawConfirmed($withdraw));
            } catch (\Exception $exception) {
                Log::critical($exception->getMessage());
                Log::critical($exception->getTraceAsString());
            }
        }

        return redirect('withdraws');
    }

    public function manual_create()
    {
        $fund = Fund::where('slug', 'tothemoon')->first();
        $users = User::all()->map(function ($user, $key) use ($fund) {
            $balance = 0;
            if (!empty($user->balance)) {
                $balance = bcadd($user->balance->body, $user->balance->bonus, 5) * $fund->token_price * (1 - self::COMMISSION);
            }

            return (object)[
                'id' => $user->id,
                'name' => $user->name,
                'balance' => $balance,
            ];
        });

        return view('withdraws.show')->with([
            'users' => $users,
        ]);
    }

    public function manual_proceed(Request $request)
    {
        try {
            $fund = Fund::where('slug', 'tothemoon')->first();
            $user = User::findOrFail($request->user);
            $amount_tkn = $request->amount / $fund->token_price;
            self::try_to_withdraw($user, $amount_tkn);
        } catch (NotEnoughMoneyToWithdraw $notEnoughMoneyToWithdraw) {
            \request()->session()->flash('status', 'Недостаточно средств для вывода!');
            return back();
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage());
            Log::critical($exception->getTraceAsString());
        }

        \request()->session()->flash('status', 'Выплата произведена!');
        return redirect()->to('/withdraws');
    }

    /**
     * @param $user
     * @param $amount_tkn
     * @return Transaction
     * @throws NotEnoughMoneyToWithdraw
     */
    public static function try_to_withdraw($user, $amount_tkn)
    {
        $fund = resolve(Fund::class);

        $balance = $user->balance;
        $body = $balance->body;
        $bonus = $balance->bonus;

        $earlier_withdraw_fee = 0.0;
        $fund_commission = 0.0;
        $took_from_body = 0.0;

        // Check investment date
        $invested_at = $user->invested_at;
        $six_month_ago = Carbon::now()->addMonths( - self::MONTHS_WITH_FEE );
        if ($six_month_ago < $invested_at) {    // Invested less than start fee less time
            $earlier_withdraw_fee = $body * self::FEE_PERCENT;
            $body -= $earlier_withdraw_fee;
        }

        $commission_percent = self::COMMISSION;
        $can_take_from_bonus = bccomp($bonus, $amount_tkn, 5) > 0;
        $can_take_from_body_and_bonus = bccomp(bcadd($body, $bonus, 5), $amount_tkn * (1 + $commission_percent), 5) > 0;
        $max_amount_usd = bcadd($body, $bonus, 5) * $fund->token_price / (1 + $commission_percent);
        $max_amount_btc = CryptoPrice::convert($max_amount_usd, 'usd', 'btc');

        if (!$can_take_from_bonus && !$can_take_from_body_and_bonus) {
            $not_enough_money = new NotEnoughMoneyToWithdraw();
            $not_enough_money->setMaxBtc($max_amount_btc);
            $not_enough_money->setMaxUsd($max_amount_usd);
            throw $not_enough_money;
        }

        // Take amount from balance
        if ($can_take_from_bonus) {
            $took_from_bonus = $amount_tkn;
            $user->balance->bonus = bcsub($bonus, $amount_tkn);
            $user->balance->save();
        } else {
            $took_from_bonus = $bonus_part = $bonus;
            $took_from_body = $body_part = bcsub($amount_tkn * (1 + $commission_percent), $bonus_part, 5);
            $fund_commission = $amount_tkn * $commission_percent;

            // TODO: commission to fund

            $user->balance->bonus = 0;
            $user->balance->body = $body - $body_part;
            $user->balance->save();
        }

        // Log transaction
        $transaction = new Transaction();
        $transaction->type = Transaction::WITHDRAW;
        $transaction->token_count = $amount_tkn;
        $transaction->token_price = $fund->token_price;
        // Magic fields specific to this kind of transaction
        $transaction->fund_commission_percent = $commission_percent;
        $transaction->fund_commission = $fund_commission;
        $transaction->earlier_withdraw_fee = $earlier_withdraw_fee;
        $transaction->took_from_bonus = (float)$took_from_bonus;
        $transaction->took_from_body = (float)$took_from_body;
        // Store to database
        $transaction->save();
        $transaction->user()->associate($user)->save();

        return $transaction;
    }

    /**
     * Return tokens to user
     *
     * @param Withdraw $withdraw
     */
    public static function cancel_withdraw(Withdraw $withdraw)
    {
        if (
            !empty($withdraw->amount)
            && !empty($withdraw->transaction)
        ) {
            // Return tokens
            $withdraw->user->balance->body += $withdraw->transaction->took_from_body;
            $withdraw->user->balance->body += $withdraw->transaction->earlier_withdraw_fee;
            $withdraw->user->balance->bonus += $withdraw->transaction->took_from_bonus;
            $withdraw->transaction->returned_at = Carbon::now();
            // Save
            $withdraw->user->balance->save();
            $withdraw->transaction->save();
            $withdraw->save();
        }
    }

}