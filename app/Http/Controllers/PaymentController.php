<?php

namespace App\Http\Controllers;

use App\Events\PaymentConfirmed;
use App\Mail\PaymentConfirmed as PaymentConfirmedMail;
use App\Fund;
use App\Payment;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    protected const PER_PAGE = 12;

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
                    'text'   => $i,
                    'link'   => url('/payments/?page=' . $i),
                    'active' => $request->get('page') == $i
                ]);
            }
        }
        return view('payments.all')->with([
            'payments' => $payments,
            'pages'    => $pages,
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
            \Event::fire(new PaymentConfirmed($payment));
            \request()->session()->flash('status', 'Пополнение подтверждено!');
            // Send email
            try {
                Mail::to(config('app.admin_email'))
                    ->cc(config('app.admin_email_alt'))
                    ->send(new PaymentConfirmedMail($payment));
            } catch (\Exception $exception) {
                Log::critical($exception->getMessage());
                Log::critical($exception->getTraceAsString());
            }
        }

        return redirect()->back();
    }

    public function manual_create()
    {
        $fund = Fund::where('slug', 'tothemoon')->first();

        return view('payments.show')->with([
            'users'       => User::all(),
            'token_price' => $fund->token_price,
        ]);
    }

    public function manual_proceed(Request $request, Fund $fund)
    {
        try {
            $user = User::findOrFail($request->user);
            $amount = (float)$request->amount;
            if ($amount > 0) {
                $user->balance->body += $amount;
                $user->balance->save();
                $fund->token_count += $amount;
                $fund->save();

                // Save transaction
                $transaction = new Transaction();
                $transaction->type = Transaction::PAYMENT;
                $transaction->token_count = $amount;
                $transaction->token_price = $fund->token_price;
                $transaction->save();
                $transaction->user()->associate($user)->save();

                \request()->session()->flash('status', 'Начисление произведено!');
            }
        } finally {
            return redirect()->to('/payments');
        }
    }
}
