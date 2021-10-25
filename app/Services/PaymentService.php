<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanPaymentStatus;
use App\Models\Payment;
use App\Providers\PaymentSucceed;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class PaymentService
{
    public function __construct()
    {
    }

    /**
     * @return Collection
     */
    public function generatePaymentRequest(): Collection
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $loans = Loan::where('status', LoanPaymentStatus::LOAN_STATUS_APPROVED)
            ->where(function ($query) use ($weekStartDate) {
                $query->orWhereNull('last_payment_request');
                $query->orWhere('last_payment_request', '<', $weekStartDate);
            })->get();
        $paymentRequests = new Collection();
        foreach ($loans as $loan) {
            $paymentRequest = new Payment();
            $paymentRequest->amount = $loan->weekly_payment_amount;
            $paymentRequest->status = LoanPaymentStatus::PAYMENT_STATUS_PENDING;
            $paymentRequest->loan_id = $loan->id;
            $paymentRequest->date_of_payment = $now->format('Y-m-d H:i');
            $paymentRequest->save();
            $paymentRequests->push($paymentRequests);
            $loan->last_payment_request = $now->format('Y-m-d H:i');
            $loan->save();
        }
        return $paymentRequests;
    }

    /**
     * @param Payment $payment
     * @return Payment|\Illuminate\Http\JsonResponse
     */
    public function setPaymentAsSuccess(Payment $payment)
    {
        if ($payment->status === LoanPaymentStatus::PAYMENT_STATUS_SUCCESS) {
            return response()->json([
                'errors' => "Payment request is already paid",
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $payment->status = LoanPaymentStatus::PAYMENT_STATUS_SUCCESS;
        $payment->save();

        PaymentSucceed::dispatch($payment);

        return $payment;
    }

    /**
     * @param Loan $loan
     * @param $payment
     * @return Loan
     */
    public function updateLoanBalance(Loan $loan, $payment): Loan
    {
        if ($payment->status === LoanPaymentStatus::PAYMENT_STATUS_SUCCESS) {
            $loan->balance = $loan->balance - $payment->amount;
        }
        return $loan;
    }
}
