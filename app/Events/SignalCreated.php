<?php

namespace App\Events;

use App\Signal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SignalCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Signal
     */
    public $signal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Signal $signal)
    {
        $this->signal = $signal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
}
