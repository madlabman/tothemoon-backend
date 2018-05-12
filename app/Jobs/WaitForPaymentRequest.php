<?php

namespace App\Jobs;

use App\Library\BlockchainHelper;
use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WaitForPaymentRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payment;

    /**
     * Create a new job instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @param BlockchainHelper $blockchainHelper
     * @return void
     */
    public function handle(BlockchainHelper $blockchainHelper)
    {
        $blockchainHelper->receive_balance_update($this->payment);
    }
}
