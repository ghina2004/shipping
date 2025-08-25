<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MyFatoorahPaymentRequest;
use App\Http\Requests\Payment\MyFatoorahVerifyRequest;
use App\Http\Resources\InvoiceOrderResource;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Services\Payment\MyFatoorahPaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class MyFatoorahPaymentController extends Controller
{
    use ResponseTrait;

    public function __construct(protected MyFatoorahPaymentService $paymentService) {}

    public function pay(MyFatoorahPaymentRequest $request): JsonResponse
    {
        $order   = Order::query()->findOrFail($request->integer('order_id'));
        $invoice = $order->orderInvoice()->firstOrFail();

        $currency = strtoupper($request->input('currency', 'USD'));

        $result = $this->paymentService->pay($invoice, $currency);

        return self::Success([
            'phase'         => $result['data']['phase'] ?? null,
            'payment_link'  => $result['data']['payment_link'],
            'mf_invoice_id' => $result['data']['mf_invoice_id'],
            'amount'        => $result['data']['amount'],
            'currency'      => $result['data']['currency'],
            'invoice'       => new InvoiceOrderResource($invoice),
        ], $result['message']);
    }

    public function verify(MyFatoorahVerifyRequest $request): JsonResponse
    {
        $invoice = OrderInvoice::where('order_id', $request->order_id)->firstOrFail();

        $out = $this->paymentService->verifyAndMarkPaid(
            $invoice,
            paymentId:   $request->input('payment_id'),
            mfInvoiceId: $request->input('mf_invoice_id')
        );

        if (($out['status'] ?? null) === 'succeeded') {
            $order = $invoice->order()->first();
            if ($order && (int)$order->status === 0) {
                $order->update(['status' => 1]);
            }
        }

        return self::Success(
            $out,
            $out['status'] === 'succeeded'
                ? (isset($order) && (int)$order->status === 1 ? 'Payment verified and order confirmed.' : 'Payment verified and recorded.')
                : ($out['message'] ?? 'Payment not completed.')
        );
    }

}
