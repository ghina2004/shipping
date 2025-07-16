<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\Order\SendOrderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class SendOrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected sendOrderService $sendOrderService) {}

    public function sendOrderToShippingManager(Order $order): JsonResponse
    {
        $orders = $this->sendOrderService->sendOrderToShippingManager($order);

        return self::Success([
            'order' => new OrderResource($orders),
        ], __('order.shipping_manager_sent'));

    }

    public function sendOrderToAccountant(Order $order): JsonResponse
    {
        $orders = $this->sendOrderService->sendOrderToAccountant($order);

        return self::Success([
            'order' => new OrderResource($orders),
        ], __('order.accountant_sent'));
    }

}
