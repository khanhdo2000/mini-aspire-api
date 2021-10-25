<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanPaymentStatus;
use App\Providers\LoanApplicationApproved;
use App\Providers\LoanApplicationRejected;
use App\Providers\LoanApplicationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoanService
{
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return Loan
     */
    public function newPost(Request $request): Loan
    {
        $loan = new Loan;
        $loan->amount = $request->get('amount') * 100;
        $loan->status = LoanPaymentStatus::LOAN_STATUS_PROCESSING;
        $loan->interest_rate = 6;
        $loan->term_in_weeks = $request->get('term_in_weeks');
        $loan->description = $request->get('description');
        $loan->user_id = $request->user()->id;
        $loan->save();

        LoanApplicationSubmitted::dispatch($loan);

        return $loan;
    }

    /**
     * @param Request $request
     * @param Loan $loan
     * @return Loan
     */
    public function update(Request $request, Loan $loan): Loan
    {
        if ($request->has('amount')) {
            $loan->amount = $request->get('amount') * 100;
        }
        if ($request->has('term_in_weeks')) {
            $loan->term_in_weeks = $request->get('term_in_weeks');
        }
        if ($request->has('interest_rate')) {
            $loan->interest_rate = $request->get('interest_rate');
        }
        $loan->status = $request->get('status');
        if (is_null($loan->weekly_payment_amount)) {
            $loan->weekly_payment_amount = round($this->calculateWeeklyPayment($loan->amount, $loan->term_in_weeks, $loan->interest_rate));
        }
        if (is_null($loan->balance)) {
            $loan->balance = $this->calculateTotalPayment($loan->amount, $loan->term_in_weeks, $loan->interest_rate);
        }
        $loan->save();

        if ($loan->status === LoanPaymentStatus::LOAN_STATUS_APPROVED) {
            LoanApplicationApproved::dispatch($loan);
        } else if ($loan->status === LoanPaymentStatus::LOAN_STATUS_REJECTED) {
            LoanApplicationRejected::dispatch($loan);
        }

        return $loan;
    }

    /**
     * @param $amount
     * @param $weeks
     * @param $interestRate
     * @return float|int
     */
    public function calculateWeeklyPayment($amount, $weeks, $interestRate)
    {
        $rate = $interestRate / 52 / 100;
        return ($rate + ($rate / (pow((1 + $rate), $weeks) - 1))) * $amount;
    }

    /**
     * @param $amount
     * @param $weeks
     * @param $interestRate
     * @return float
     */
    public function calculateTotalPayment($amount, $weeks, $interestRate): float
    {
        $weeklyPayment = $this->calculateWeeklyPayment($amount, $weeks, $interestRate);
        return round($weeklyPayment * $weeks);
    }
}
