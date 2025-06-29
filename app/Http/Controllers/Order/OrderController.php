<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\orderResource;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\Status;
use App\Services\Order\orderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected orderService $orderService) {}

    public function showEmployeeOrders(): JsonResponse
    {
        $orders = $this->orderService->showEmployeeOrders();

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.employee_orders_listed'));
    }

    public function showOrder($orderId): JsonResponse
    {
        $orders = $this->orderService->showOrder($orderId);

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.order_details_retrieved'));
    }

    public function showShipmentsOrder($orderId): JsonResponse
    {
        $shipments = $this->orderService->showShipmentsOrder($orderId);
        return self::Success([
            'shipments' => ShipmentResource::collection($shipments),
        ], __('order.shipments_listed'));
    }

    public function updateOrderStatus(Order $order,Status $status): JsonResponse
    {
        $order = $this->orderService->updateOrderStatus($order ,$status);
        return self::Success([
            'order' => new OrderResource($order),
        ], __('order.shipments_updated'));
    }

}
