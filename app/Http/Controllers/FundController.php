<?php

namespace App\Http\Controllers;

use App\Fund;
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

    public function validateFundRequest(Request $request)
    {
        return $request->validate([
            'token_count'           => 'required|numeric',
            'token_price'           => 'required|numeric',
            'manual_balance_usd'    => 'required|numeric'
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
                $data['token_count'] = intval($data['token_count']);
                $fund->update($data);
            }
        }
        $request->session()->flash('status', 'Фонд обновлен!');
        return redirect()->to('fund');
    }
}
