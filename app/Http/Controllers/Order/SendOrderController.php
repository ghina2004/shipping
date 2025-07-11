<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderResource;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\Status;
use App\Services\Order\OrderRequestService;
use App\Services\Order\orderService;
use App\Services\Order\SendOrderService;
use App\Services\Shipment\ShipmentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendOrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected sendOrderService $sendOrderService) {}

    public function sendOrderToShippingManager(Order $order): JsonResponse
    {
        $orders = $this->sendOrderService->sendOrderToShippingManager($order);

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.shipping_manager_sent'));

    }

    public function sendOrderToAccountant(Order $order): JsonResponse
    {
        $orders = $this->sendOrderService->sendOrderToAccountant($order);

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.accountant_sent'));
    }

}
