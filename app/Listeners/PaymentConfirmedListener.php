<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Fund;
use App\Library\CryptoPrice;
use App\Mail\PaymentConfirmed as PaymentConfirmedMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentConfirmedListener
{
    protected $fund;

    /**
     * Create the event listener.
     *
     * @param Fund $fund
     */
    public function __construct(Fund $fund)
    {
        $this->fund = $fund;
    }

    /**
     * Handle the event.
     *
     * @param PaymentConfirmed $event
     * @param Fund $fund
     * @return void
     */
    public function handle(PaymentConfirmed $event)
    {
        $event->payment->is_confirmed = true;
        $first_investment = false;
        // Check investment date
        if (empty($event->payment->user->invested_at)) {
            $first_investment = true;
            $event->payment->user->invested_at = Carbon::now();
            $event->payment->user->save();
        } else {
            // TODO: reinvest
        }

        if (!empty($this->fund) && $this->fund->token_price > 0) {
            // Getting values
            $btc_amount = $event->payment->amount;
            $usd_amount = CryptoPrice::convert($btc_amount, 'btc', 'usd');
            $tkn_amount = $usd_amount / $this->fund->token_price;
            // Update user balance
            if ($first_investment) {
                $event->payment->user->balance->primary_usd = $usd_amount;
            }
            $event->payment->user->balance->body += $tkn_amount;
            $event->payment->user->balance->save();
            $event->payment->save();
            // Update fund tokens count
            $this->fund->token_count += $tkn_amount;
            $this->fund->save();
        }

        // Send email
        try {
            Mail::to(config('app.admin_email'))
                ->cc(config('app.admin_email_alt'))
                ->send(new PaymentConfirmedMail($event->payment));
        } catch (\Exception $exception) {
            Log::critical($exception->getMessage());
            Log::critical($exception->getTraceAsString());
        }
    }
}
