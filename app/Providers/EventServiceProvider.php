<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LoanApplicationSubmitted::class => [
            LoanApplicationSubmittedNotification::class,
        ],
        LoanApplicationApproved::class => [
            LoanApplicationApprovedNotification::class,
        ],
        LoanApplicationRejected::class => [
            LoanApplicationRejectedNotification::class,
        ],
        PaymentRequestCreated::class => [
            PaymentRequestCreatedNotification::class,
        ],
        PaymentSucceed::class => [
            UpdateLoanBalance::class,
            PaymentSucceedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
