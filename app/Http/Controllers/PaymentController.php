<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Payment;
use App\Providers\PaymentSucceed;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * PaymentController constructor.
     * @param PaymentService $paymentService
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * This API mimic payment notification from a third party payment service update set payment as paid (success)
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function put(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        if ($request->has('paymentSuccess') && $request->get('paymentSuccess') === true) {
            $this->paymentService->setPaymentAsSuccess($payment);
        }

        return response()->json([
            'message' => "Success",
        ], Response::HTTP_OK);
    }

    /**
     * This api manually check for loan that need payment request to be generated
     *
     * @return JsonResponse
     */
    public function generatePaymentRequest()
    {
        $this->paymentService->generatePaymentRequest();
        return response()->json();
    }

    /**
     * Customer get payment detail
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function get(Request $request, $id)
    {
        try {
            $loan = Payment::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        return $loan;
    }
}
