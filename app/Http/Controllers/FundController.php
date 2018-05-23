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
}
