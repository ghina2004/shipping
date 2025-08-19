<?php

namespace App\Services;

use App\Http\Requests\OrderTrackingLogRequest;
use App\Models\Order;
use App\Models\OrderTrackingLog;

class OrderTrackingLogService
{
    public function create(array $data)
    {
        return OrderTrackingLog::create($data);
    }

    public function show(OrderTrackingLog $orderTrackingLog)
    {
        return $orderTrackingLog;
    }

    public function update(OrderTrackingLog $orderTrackingLog, array $data)
    {

        $orderTrackingLog->update($data);
        return $orderTrackingLog;
    }

    public function delete(OrderTrackingLog $orderTrackingLog)
    {
        $orderTrackingLog->delete();
    }

    public function addTrackingLogByOrderId( Order $order, array $data)
    {
        return $order->trackingLogs()->create($data);
    }
}
