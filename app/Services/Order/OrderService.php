<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

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

}
