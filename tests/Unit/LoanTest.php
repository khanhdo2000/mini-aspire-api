<?php

namespace Tests\Unit;

use App\Models\Loan;
use App\Models\Payment;
use App\Services\LoanService;
use App\Services\PaymentService;
use Tests\TestCase;

class LoanTest extends TestCase
{
    public function testWeeklyPaymentCalculation()
    {
        $amount = 10000000;
        $weeks = 520;
        $interestRate = 6;
        $weeklyPayment = (new LoanService())->calculateWeeklyPayment($amount, $weeks, $interestRate);
        $this->assertEquals(25584, round($weeklyPayment));
    }

    public function testTotalPaymentCalculation()
    {
        $amount = 10000000;
        $weeks = 520;
        $interestRate = 6;
        $weeklyPayment = (new LoanService())->calculateWeeklyPayment($amount, $weeks, $interestRate);
        $totalPayment = $weeklyPayment * $weeks;
        $this->assertEquals(13303814, round($totalPayment));
    }

    public function testLoanBalancePaymentPending()
    {
        $loan = Loan::factory()->make();
        $loan->balance = (new LoanService())->calculateTotalPayment($loan->amount, $loan->term_in_weeks, $loan->interest_rate);
        $payment = Payment::factory()->make([
            'status' => 'pending'
        ]);
        $loan = (new PaymentService())->updateLoanBalance($loan, $payment);
        $this->assertEquals(13303814, $loan->balance);
    }

    public function testLoanBalancePaymentSuccess()
    {
        $loan = Loan::factory()->make([
            'description' => 'test'
        ]);
        $loan->weekly_payment_amount = (new LoanService())->calculateWeeklyPayment($loan->amount, $loan->term_in_weeks, $loan->interest_rate);
        $loan->balance = (new LoanService())->calculateTotalPayment($loan->amount, $loan->term_in_weeks, $loan->interest_rate);
        $originalBalance = $loan->balance;
        $payment = Payment::factory()->make([
            'status' => 'success'
        ]);
        $loan = (new PaymentService())->updateLoanBalance($loan, $payment);
        $this->assertEquals($originalBalance - round($loan->weekly_payment_amount), $loan->balance);
    }
}
