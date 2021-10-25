<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\Payment;
use App\Providers\PaymentSucceed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PaymentSucceedNotification
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
     * @param  PaymentSucceed  $event
     * @return void
     */
    public function handle(PaymentSucceed $event)
    {
        $payment = Payment::with('loan', 'loan.user')->find($event->payment->id);
        $email = $payment->loan->user->email;
        Log::info("Send success payment notification to $email");
    }
}
