<?php

namespace App\Http\Controllers;

use App\Coin;
use App\Fund;
use App\FundBalanceHistory;
use App\LevelCondition;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function manual_usd($fund_id, Request $request)
    {
        $fund = Fund::findOrFail($fund_id);
        $fund->manual_balance_usd = $request->post('manual_balance_usd');
        $fund->save();
        return redirect()->back();
    }

    public function index()
    {
        $fund = Fund::where('slug', '=', 'tothemoon')->first();
        if (empty($fund)) app()->abort(404);
        return view('fund.show')->with([
            'fund' => $fund,
        ]);
    }

    public function validateFundRequest(Request $request)
    {
        return $request->validate([
            'token_count'        => 'required|numeric',
            'token_price'        => 'required|numeric',
            'manual_balance_usd' => 'required|numeric',
            'coin'               => 'required|array',
        ], [
            'required' => 'Поле обязательно',
            'numeric'  => 'Неверный формат',
            'array'    => null,
        ]);
    }

    public function update(Request $request)
    {
        if ($data = $this->validateFundRequest($request)) {
            $fund = Fund::where('slug', 'tothemoon')->first();
            if (!empty($fund)) {
                $fund->update($data);
                foreach ($data['coin'] as $sym => $amount) {
                    $db_coin = $fund->coins()->where('symbol', $sym)->first();
                    if (!empty($db_coin)) {
                        $db_coin->update(compact('amount'));
                    } else {
                        $db_coin = Coin::create([
                            'symbol' => $sym,
                            'amount' => $amount,
                        ]);
                        $fund->coins()->save($db_coin);
                    }
                }
            }
        }
        $request->session()->flash('status', 'Фонд обновлен!');
        return redirect()->to('fund');
    }

    public function delete_coin($fund_id, Request $request)
    {
        /** @var Fund $fund */
        $fund = Fund::findOrFail($fund_id);
        $symbol = $request->get('symbol');
        $coin = $fund->coins()->where('symbol', $symbol)->first();
        if (!empty($coin)) {
            $coin->delete();
            return response()->json([], 200);
        }

        return response()->json(500);
    }

    public function balance_history(Request $request)
    {
        $items = FundBalanceHistory::latest();

        if (!empty($request->get('date_from'))) {
            $items = $items->where(
                'created_at', '>=', Carbon::parse($request->get('date_from'), config('app.TZ'))
            );
        }

        if (!empty($request->get('date_to'))) {
            $items = $items->where(
                'created_at', '<=', Carbon::parse($request->get('date_to'), config('app.TZ'))
            );
        }

        return view('fund.balance-history')->with([
            'history'   => $items->limit(30)->get(),
            'date_from' => $request->get('date_from'),
            'date_to'   => $request->get('date_to')
        ]);
    }
}
