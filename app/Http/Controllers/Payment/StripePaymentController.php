<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StripePaymentRequest;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Services\Payment\StripePaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class StripePaymentController extends Controller
{
    use ResponseTrait;
    public function __construct(protected StripePaymentService $paymentService) {}

    public function pay(Order $order): JsonResponse
    {
        $invoice = $order->orderInvoice;

        $result = $this->paymentService->pay($invoice);

        return self::Success($result['data'], $result['message']);
    }

    public function verify(StripePaymentRequest $request): JsonResponse
    {
        $order = Order::query()->findOrFail($request['order_id']);
        $invoice = $order->orderInvoice;

        $result = $this->paymentService->verifyAndMarkPaid($invoice, $request['payment_intent_id']);

        return self::Success(
            $result,
            $result['status'] === 'succeeded'
                ? 'Payment verified and recorded.'
                : 'Payment not yet succeeded.'
        );
    }
}
