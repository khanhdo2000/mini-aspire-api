<?php


namespace App\Models;


class LoanPaymentStatus
{
    public const LOAN_STATUS_PROCESSING = 'processing';
    public const LOAN_STATUS_APPROVED = 'approved';
    public const LOAN_STATUS_REJECTED = 'rejected';
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_SUCCESS = 'success';
}
