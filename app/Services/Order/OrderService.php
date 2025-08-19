<?php

namespace App\Services\Order;

use App\Exceptions\Types\CustomException;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Status;
use App\Services\Invoice\OrderInvoiceService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;


class OrderService
{


    public function __construct(protected OrderInvoiceService $invoiceService) {}

    public function showEmployeeOrders(): Collection
    {
        return Order::query()->where('employee_id',auth()->user()->id)->get();
    }

    public function showShippingManagerOrders(): Collection
    {
        return Order::query()->where('shipping_manager_id',auth()->user()->id)->get();
    }

    public function showAccountantOrders(): Collection
    {
        return Order::query()->where('accountant_id',auth()->user()->id)->get();
    }

    public function showOrder($orderId): Collection
    {
        return Order::query()->where('id',$orderId)->get();
    }

    public function showShipmentsOrder($orderId): Collection
    {
        return Shipment::query()->where('order_id', $orderId)->get();
    }

    public function updateOrderStatus(Order $order,Status $status): Order
    {
        $order->update(['status' => $status['name']]);
        return $order;
    }

    public function getUnconfirmedOrders()
    { $user = Auth::user()->load(['orderCustomers' => function ($query) {
        $query->where('status', false);
    }]);
        return $user->orderCustomers;
    }
    public function getConfirmedOrders()
    {
        $user = Auth::user()->load(['orderCustomers' => function ($query) {
            $query->where('status', true);
        }]);
        return $user->orderCustomers;
    }

    public function confirmOrder(Order $order)
    {

        if ($order->status === 1) {

            throw new CustomException(('Order is already confirmed.'), 422);

        }

        $order->load('shipments');

        $unconfirmed = $order->shipments->contains(function ($shipment) {
            return $shipment->is_confirm == 0;
        });

        if ($unconfirmed) {

            throw new CustomException(('Not all shipments are confirmed.'), 422);
        }

        $this->invoiceService->createOrderInvoice($order);

        $order->update(['status' => 1]);

        return $order;
    }




}
