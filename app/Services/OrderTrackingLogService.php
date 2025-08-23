<?php

namespace App\Services;

use App\Http\Requests\ShipmentTrackingLogRequest;
use App\Models\Order;
use App\Models\ShipmentTrackingLog;

class OrderTrackingLogService
{
    public function create(array $data)
    {
        return ShipmentTrackingLog::create($data);
    }

    public function show(ShipmentTrackingLog $orderTrackingLog)
    {
        return $orderTrackingLog;
    }

    public function update(ShipmentTrackingLog $orderTrackingLog, array $data)
    {

        $orderTrackingLog->update($data);
        return $orderTrackingLog;
    }

    public function delete(ShipmentTrackingLog $orderTrackingLog)
    {
        $orderTrackingLog->delete();
    }

//    public function addTrackingLogByOrderId( Order $order, array $data)
//    {
//        return $order->trackingLogs()->create($data);
//    }
}
