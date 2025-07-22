<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

class OrderRequestService
{
    public function showEmployeeOrderRequest(): Collection
    {
        return Order::query()->where('employee_id',null)->get();
    }

    public function acceptEmployeeOrder(Order $order): Order
    {
        $order->update(['employee_id'=>auth()->id()]);
        return $order;
    }


}
