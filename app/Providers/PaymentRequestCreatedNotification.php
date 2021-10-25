<?php

namespace App\Providers;

use App\Providers\PaymentRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaymentRequestCreatedNotification
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
     * @param  PaymentRequestCreated  $event
     * @return void
     */
    public function handle(PaymentRequestCreated $event)
    {
        //
    }
}
