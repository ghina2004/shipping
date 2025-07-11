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
use App\Services\Shipment\ShipmentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestOrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected orderRequestService $orderRequestService) {}

    public function showEmployeeOrderRequest(): JsonResponse
    {
        $orders = $this->orderRequestService->showEmployeeOrderRequest();

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.employee_orders_request_listed'));

    }

    public function acceptEmployeeOrder(Order $order): JsonResponse
    {
        $orders = $this->orderRequestService->acceptEmployeeOrder($order);

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.employee_accept_order'));
    }

    public function showAccountantOrderRequest(): JsonResponse
    {
        $orders = $this->orderRequestService->showAccountantOrderRequest();

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.accountant_orders_request_listed'));

    }

    public function acceptAccountantOrder(Order $order): JsonResponse
    {
        $orders = $this->orderRequestService->acceptAccountantOrder($order);

        return self::Success([
            'order' => orderResource::collection($orders),
        ], __('order.accountant_accept_order'));
    }

}
