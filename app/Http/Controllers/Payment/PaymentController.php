<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\OrderInvoice;
use App\Services\Payment\StripePaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ResponseTrait;
    public function __construct(protected StripePaymentService $paymentService) {}

    public function pay(OrderInvoice $invoice): JsonResponse
    {
        $result = $this->paymentService->pay($invoice);

        return self::Success($result['data'], $result['message']);
    }

    public function verify(PaymentRequest $request): JsonResponse
    {
        $invoice = OrderInvoice::query()->findOrFail($request['invoice_id']);

        $result = $this->paymentService->verifyAndMarkPaid($invoice, $request['payment_intent_id']);

        return self::Success(
            $result,
            $result['status'] === 'succeeded'
                ? 'Payment verified and recorded.'
                : 'Payment not yet succeeded.'
        );
    }
}
