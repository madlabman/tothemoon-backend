<?php

namespace App\Mail;

use App\Withdraw;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WithdrawConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Withdraw */
    public $withdraw;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Withdraw $withdraw)
    {
        $this->withdraw = $withdraw;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@tothemoon.com')
                    ->view('emails.withdraw-confirmed');
    }
}
