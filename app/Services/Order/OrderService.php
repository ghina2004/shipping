<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function create(Order $order)
    {

    }

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

    public function getConfirmedOrdersForUser()
    {
        $user = Auth::user()->load(['orderCustomers' => function ($query) {
            $query->where('status', true)->with('shipments');
        }]);
        return $user->orderCustomers;
    }


    public function getUnconfirmedOrdersForUser()
    { $user = Auth::user()->load(['orderCustomers' => function ($query) {
        $query->where('status', false)->with('shipments');
    }]);
        return $user->orderCustomers;
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



}
