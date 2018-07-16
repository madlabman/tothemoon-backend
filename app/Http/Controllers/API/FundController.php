<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Profit;

class FundController extends Controller
{
    public function profit()
    {
        $data = Profit::oldest()->get()->map(function ($profit) {
            return [
                'date' => $profit->created_at->toDateString(),
                'close' => $profit->usd_change
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function token_price()
    {
        $data = Profit::oldest()->get()->map(function ($profit) {
            return [
                'date' => $profit->created_at->toDateString(),
                'close' => $profit->token_price
            ];
        });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}