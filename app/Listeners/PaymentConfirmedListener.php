<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use App\Fund;
use App\Library\CryptoPrice;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentConfirmedListener
{
    protected $fund;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param PaymentConfirmed $event
     * @param Fund $fund
     * @return void
     */
    public function handle(PaymentConfirmed $event, Fund $fund)
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

        if (!empty($fund) && $fund->token_price > 0) {
            // Getting values
            $btc_amount = $event->payment->amount;
            $usd_amount = CryptoPrice::convert($btc_amount, 'btc', 'usd');
            $tkn_amount = $usd_amount / $fund->token_price;
            // Update user balance
            if ($first_investment) {
                $event->payment->user->balance->primary_usd = $usd_amount;
            }
            $event->payment->user->balance->body += $tkn_amount;
            $event->payment->user->balance->save();
            $event->payment->save();
            // Update fund tokens count
            $fund->token_count += $tkn_amount;
            $fund->save();
        }
    }
}
