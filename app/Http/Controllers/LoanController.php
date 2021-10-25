<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Providers\LoanApplicationApproved;
use App\Providers\LoanApplicationRejected;
use App\Providers\LoanApplicationSubmitted;
use App\Services\LoanService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoanController extends Controller
{
    /**
     * @var LoanService
     */
    private $loanService;

    /**
     * LoanController constructor.
     * @param LoanService $loanService
     */
    public function __construct(LoanService $loanService)
    {

        $this->loanService = $loanService;
    }

    /**
     * Customer submit new loan application
     *
     * @param Request $request
     * @return Loan
     */
    public function post(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'integer'],
            'term_in_weeks' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        return $this->loanService->newPost($request);
    }

    /**
     * BA update loan status (approve/reject)
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function put(Request $request, $id)
    {
        $request->validate([
            'amount' => ['integer'],
            'term_in_weeks' => ['integer'],
            'interest_rate' => ['integer'],
            'status' => ['required', 'in:processing,approved,rejected'],
        ]);

        try {
            $loan = Loan::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $loan = $this->loanService->update($request, $loan);

        return response()->json($loan);
    }
}
