<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\Status;
use App\Services\Order\OrderRequestService;
use App\Services\Order\OrderService;
use App\Services\Shipment\ShipmentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected orderService $orderService, protected orderRequestService $orderRequestService) {}

    public function showEmployeeOrders(): JsonResponse
    {
        $orders = $this->orderService->showEmployeeOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], __('order.employee_orders_listed'));
    }

    public function showShippingManagerOrders(): JsonResponse
    {
        $orders = $this->orderService->showShippingManagerOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], __('order.employee_orders_listed'));
    }

    public function showAccountantOrders(): JsonResponse
    {
        $orders = $this->orderService->showAccountantOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], __('order.employee_orders_listed'));
    }

    public function showOrder($orderId): JsonResponse
    {
        $orders = $this->orderService->showOrder($orderId);

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], __('order.order_details_retrieved'));
    }

    public function showShipmentsOrder($orderId): JsonResponse
    {
        $shipments = $this->orderService->showShipmentsOrder($orderId);
        return self::Success([
            'shipments' => ShipmentResource::collection($shipments),
        ], __('order.shipments_listed'));
    }



    public function confirmedOrders()
    {
        $orders = $this->orderService->getConfirmedOrdersForUser();

        return self::Success([
            'order' =>  OrderResource::collection($orders),
        ], __('success'));
    }

    public function unconfirmedOrders(): JsonResponse
    {
        $orders = $this->orderService->getUnconfirmedOrdersForUser();
        return self::Success([
            'order' =>  OrderResource::collection($orders),
        ], __('success'));
    }

    public function showConfirmedOrders()
    {
        $orders = $this->orderService->getConfirmedOrders();

        return self::Success([
            'order' =>  OrderResource::collection($orders),
        ], __('success'));
    }

    public function showUnconfirmedOrders()
    {
        $orders = $this->orderService->getUnconfirmedOrders();

        return self::Success([
            'order' =>  OrderResource::collection($orders),
        ], __('success'));
    }

}
