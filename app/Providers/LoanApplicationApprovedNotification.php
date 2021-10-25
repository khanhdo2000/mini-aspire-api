<?php

namespace App\Providers;

use App\Models\Loan;
use Illuminate\Support\Facades\Log;

class LoanApplicationApprovedNotification
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
     * @param  LoanApplicationApproved  $event
     * @return void
     */
    public function handle(LoanApplicationApproved $event)
    {
        $loan = Loan::with('user')->find($event->loan->id)->first();
        $email = $loan->user->email;
        Log::info("Send Application Approved notification to $email via email/phone");
    }
}
