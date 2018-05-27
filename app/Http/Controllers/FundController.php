<?php

namespace App\Http\Controllers;

use App\Fund;
use App\LevelCondition;
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
            'fund'      => $fund,
            'levels'    => LevelCondition::orderBy('min_usd_amount')->orderBy('max_duration')->get(),
        ]);
    }

    public function validateFundRequest(Request $request)
    {
        return $request->validate([
            'token_count'           => 'required|numeric',
            'token_price'           => 'required|numeric',
            'manual_balance_usd'    => 'required|numeric',
            'manual_balance_btc'    => 'required|numeric',
            'manual_balance_eth'    => 'required|numeric',
        ], [
            'required'  => 'Поле обязательно',
            'numeric'   => 'Неверный формат',
        ]);
    }

    public function update(Request $request)
    {
        if ($data = $this->validateFundRequest($request)) {
            $fund = Fund::where('slug', 'tothemoon')->first();
            if (!empty($fund)) {
                $fund->update($data);
            }
        }
        $request->session()->flash('status', 'Фонд обновлен!');
        return redirect()->to('fund');
    }
}
