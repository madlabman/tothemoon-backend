<?php

namespace App\Listeners;

use App\Events\SignalCreated;
use App\Library\Firebase;
use App\User;

class SignalCreatedListener
{
    private $firebase;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->firebase = new Firebase();
    }

    /**
     * Handle the event.
     *
     * @param  SignalCreated  $event
     * @return void
     */
    public function handle(SignalCreated $event)
    {
        // Send notifications on created public signal.
        $users = User::where('open_signal_access', true)->get();
        $this->firebase->send_notifications_to_users($users, 'Новый сигнал!', $event->signal->info);
    }
}
