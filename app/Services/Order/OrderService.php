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
        return Auth::user()->orderCustomers()->where('status', true)->get();
    }

    public function getUnconfirmedOrdersForUser()
    {
        return Auth::user()->orderCustomers()->where('status', false)->get();
    }

}
