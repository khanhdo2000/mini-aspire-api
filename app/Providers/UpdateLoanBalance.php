<?php

namespace App\Providers;

use App\Models\Loan;
use App\Models\Payment;
use App\Providers\PaymentSucceed;
use App\Services\LoanService;
use App\Services\PaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateLoanBalance
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
     * @param PaymentSucceed $event
     * @return void
     */
    public function handle(PaymentSucceed $event)
    {
        $payment = Payment::with('loan')->find($event->payment->id);
        $loan = Loan::find($payment->loan->id);
        $loan = (new PaymentService())->updateLoanBalance($loan, $payment);
        $loan->save();
    }
}
