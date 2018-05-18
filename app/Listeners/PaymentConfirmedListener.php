<?php

namespace App\Listeners;

use App\Events\PaymentConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentConfirmedListener
{
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
     * @param  PaymentConfirmed  $event
     * @return void
     */
    public function handle(PaymentConfirmed $event)
    {
        $event->payment->is_confirmed = true;
        // Update user balance
        $event->payment->user->balance->body += $event->payment->amount;
        $event->payment->user->balance->save();
        $event->payment->save();
    }
}
