<?php

namespace App\Console\Commands;

use App\Events\PaymentConfirmed;
use App\Library\BlockchainHelper;
use App\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

class CheckBTCPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tothemoon:checkBTC';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check configured address for incoming payments.';

    protected $address;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->address = config('app.BTC_ADDRESS');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->address !== null) {

            $txs = BlockchainHelper::get_transactions($this->address);
            if (empty($txs)) return;
            foreach ($txs['txs'] as $tx) {
                // Check
                $tx_time = $tx['time'];
                $hash = $tx['hash'];
                $with_hash_count = Payment::where('tx_hash', '=', $hash)->count();
                if ($with_hash_count > 0) continue;  // Skip used transaction
                // Get input
                foreach ($tx['inputs'] as $input) {
                    $addr = $input['prev_out']['addr'];
                    if ($addr == $this->address) continue;
                    // Find all unconfirmed payments in database
                    if (!empty($payments = Payment::all()->where('wallet', '=', $addr)
                        ->where('is_confirmed', '=', false))) {
                        $amount = null;
                        // Find output with OUR wallet
                        foreach ($tx['out'] as $out) {
                            if ($out['addr'] == config('app.BTC_ADDRESS')) {
                                $amount = $out['value'] / 100000000;    // Convert from satoshi
                                break;
                            }
                        }
                        // If transaction was found
                        if (!empty($amount)) {
                            // Get payment with similar amount
                            // and no older than tx creation time
                            $time = Carbon::createFromTimestamp($tx_time)->toDateTimeString();
                            $payment = Payment::where('wallet', '=', $addr)
                                ->where('is_confirmed', '=', false)
                                ->where('amount', '=', (string)$amount)
                                ->where('created_at', '<=', $time)
                                ->first();
                            // Save confirmation
                            if (!empty($payment)) {
                                // Assign transaction to payment
                                $payment->tx_hash = $hash;
                                $payment->save();
                                // Dispatch event to update balance
                                Event::fire(new PaymentConfirmed($payment));
                            }
                        }
                    } else {
                        continue;
                    }
                }
            }

        }
    }
}
