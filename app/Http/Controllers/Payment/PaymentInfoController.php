<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Question\PaymentInfoResource;
use App\Models\Order;
use App\Services\Payment\PaymentInfoService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class PaymentInfoController extends Controller
{
    use ResponseTrait;
    public function __construct(protected PaymentInfoService $paymentService) {}

    public function show(Order $order): JsonResponse
    {
        $payment = $this->paymentService->show($order);

        return self::Success([
            'info' => new PaymentInfoResource($payment),
        ], 'info shown successfully');
    }

}
