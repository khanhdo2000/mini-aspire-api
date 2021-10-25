<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoanApplicationRejected
{
    use Dispatchable, SerializesModels;

    public $loan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($loan)
    {
        $this->loan = $loan;
    }
}
