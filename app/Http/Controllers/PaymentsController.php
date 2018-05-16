<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    protected const PER_PAGE = 10;

    public function index(Request $request)
    {
        $skip = 0;
        if (!empty($page = $request->get('page'))) {
            $skip = ($page - 1) * self::PER_PAGE;
        }
        $payments = Payment::skip($skip)->take(self::PER_PAGE)->latest()->get();
        $count = Payment::count();
        $pages = [];
        if (self::PER_PAGE < $count) {
            for ($i = 1; $i <= ceil($count / self::PER_PAGE); $i++) {
                array_push($pages, [
                    'text'      => $i,
                    'link'      => url('/payments/?page=' . $i),
                    'active'    => $request->get('page') == $i
                ]);
            }
        }
        return view('payments.all')->with([
            'payments'     => $payments,
            'pages'         => $pages,
        ]);
    }

    public function delete($id)
    {
        $payment = Payment::find($id);
        if (!empty($payment)) {
            $payment->delete();
            \request()->session()->flash('status', 'Пополнение удалено!');
        }

        return redirect('payments');
    }

    public function confirm($id)
    {
        $payment = Payment::find($id);
        if (!empty($payment)) {
            $payment->is_confirmed = true;
            $payment->save();
            \request()->session()->flash('status', 'Пополнение подтверждено!');
        }

        return redirect()->back();
    }
}
