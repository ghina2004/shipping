<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MyFatoorahPaymentRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Question\MyFatoorahResource;
use App\Http\Resources\ShipmentResource;
use App\Models\Order;
use App\Services\Order\OrderRequestService;
use App\Services\Order\OrderService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct(protected orderService $orderService, protected orderRequestService $orderRequestService)
    {
    }

    public function showEmployeeOrders(): JsonResponse
    {
        $orders = $this->orderService->showEmployeeOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('order.employee_orders_listed'));
    }

    public function showShippingManagerOrders(): JsonResponse
    {
        $orders = $this->orderService->showShippingManagerOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('order.employee_orders_listed'));
    }

    public function showAccountantOrders(): JsonResponse
    {
        $orders = $this->orderService->showAccountantOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('order.employee_orders_listed'));
    }

    public function showOrder($orderId): JsonResponse
    {
        $orders = $this->orderService->showOrder($orderId);

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('order.order_details_retrieved'));
    }

    public function deleteOrder($orderId): JsonResponse
    {
        $orders = $this->orderService->deleteOrder($orderId);

        return self::Success([], ('order.order_delete'));
    }

    public function showShipmentsOrder($orderId): JsonResponse
    {
        $shipments = $this->orderService->showShipmentsOrder($orderId);

        return self::Success([
            'shipments' => ShipmentResource::collection($shipments),
        ], ('order.shipments_listed'));
    }


    public function showConfirmedOrders()
    {
        $orders = $this->orderService->getConfirmedOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('success'));
    }

    public function showDeliveredOrders()
    {
        $orders = $this->orderService->getDeliveredOrder();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('success'));
    }

    public function showUnconfirmedOrders()
    {
        $orders = $this->orderService->getUnconfirmedOrders();

        return self::Success([
            'order' => OrderResource::collection($orders),
        ], ('success'));
    }

    public function changeStatusToConfirm(MyFatoorahPaymentRequest $request): JsonResponse
    {
        $currency = strtoupper($request->input('currency', 'USD'));

        $order = Order::query()->findOrFail($request->integer('order_id'));

        $data = $this->orderService->confirmOrder($order, $currency);

        return self::Success(new MyFatoorahResource($data), 'Payment link generated. Complete payment to confirm the order.');
    }
}
