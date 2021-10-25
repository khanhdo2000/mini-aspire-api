<?php

namespace App\Providers;

use App\Models\Loan;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class LoanApplicationSubmittedNotification
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
     * @param  LoanApplicationSubmitted  $event
     * @return void
     */
    public function handle(LoanApplicationSubmitted $event)
    {
        $loan = Loan::with('user')->find($event->loan->id)->first();
        $email = $loan->user->email;
        Log::info("Send Application received notification to $email via email/phone");
        //send notification to admin/business analyst
    }
}
