<?php

namespace App\Http\Controllers\API;

use App\Fund;
use App\Http\Controllers\Controller;

class ProfitController extends Controller
{
    public function all()
    {
        try {
            $fund = Fund::where('slug', '=', 'tothemoon')->first();
            if (!empty($fund)) {
                $profits = $fund->profits()->take(7)->latest()->get();
                return response()->json([
                    'status'    => 'success',
                    'profits'   => $profits,
                ]);
            }

            throw new \Exception('Fund not found.');
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
            ], 500);
        }
    }
}