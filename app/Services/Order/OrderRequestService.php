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

    public function showAccountantOrderRequest(): Collection
    {
        return Order::query()->where('accountant_id',null)
            ->where('has_accountant',1)
            ->get();
    }

    public function acceptEmployeeOrder(Order $order): Order
    {
        $order->update(['employee_id'=>auth()->id()]);
        return $order;
    }

    public function acceptAccountantOrder(Order $order): Order
    {
        $order->update(['accountant_id'=>auth()->id()]);
        return $order;
    }
}
