<?php

namespace App\Providers;

use App\Models\Loan;
use App\Providers\LoanApplicationRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LoanApplicationRejectedNotification
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
     * @param  LoanApplicationRejected  $event
     * @return void
     */
    public function handle(LoanApplicationRejected $event)
    {
        $loan = Loan::with('user')->find($event->loan->id)->first();
        $email = $loan->user->email;
        Log::info("Send Application Rejected notification to $email via email/phone");
    }
}
