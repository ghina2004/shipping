<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\Status;
use Illuminate\Database\Eloquent\Collection;

class SendOrderService
{
    public function sendOrderToShippingManager(Order $order): Order
    {
        $order->update(['shipping_manager_id'=>2]);
        return $order;
    }

    public function sendOrderToAccountant(Order $order): Order
    {
        $order->update(['accountant_id'=>5]);
        return $order;
    }
}
