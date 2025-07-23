<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\OrderInvoice;
use App\Services\Payment\StripePaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(protected StripePaymentService $paymentService) {}

    public function initial(OrderInvoice $invoice): JsonResponse
    {
        $payment = $this->paymentService->handleInitialPayment($invoice);
        return response()->json(['status' => 1, 'data' => $payment, 'message' => 'Initial payment created.']);
    }

    public function remaining(OrderInvoice $invoice): JsonResponse
    {
        $payment = $this->paymentService->handleRemainingPayment($invoice);
        return response()->json(['status' => 1, 'data' => $payment, 'message' => 'Remaining payment completed.']);
    }
}
