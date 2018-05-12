<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return view('payments.all')->with(compact('payments'));
    }

    public function delete($id)
    {
        $payment = Payment::find($id);
        if (!empty($payment)) {
            $payment->delete();
        }

        return redirect('payments');
    }

    public function confirm($id)
    {
        $payment = Payment::find($id);
        if (!empty($payment)) {
            $payment->is_confirmed = true;
            $payment->save();
        }

        return redirect()->back();
    }
}
