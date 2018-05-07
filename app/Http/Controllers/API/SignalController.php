<?php

namespace App\Http\Controllers\API;

class SignalController
{
    public function all()
    {
        try {
            $signals = auth()->user()->signals();
            return response()->json([
                'status'    => 'success',
                'signals'   => $signals,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status'    => 'error',
            ], 500);
        }
    }
}