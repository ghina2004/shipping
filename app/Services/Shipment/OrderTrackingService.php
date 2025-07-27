<?php

namespace App\Services\Shipment;

use App\Enums\Status\OrderTrackingStatus;
use App\Http\Requests\OrderRouteRequest;
use App\Models\OrdertRoute;
use App\Traits\ResponseTrait;

class OrderTrackingService
{
    use ResponseTrait;

    public function store(OrderRouteRequest $request)
    {
        $route = OrdertRoute::create($request->validated());
        return self::Success(new OrderRouteResource($route), 'تم إنشاء مسار الطلب بنجاح');
    }

    public function update(OrderRouteRequest $request, OrdertRoute $orderRoute)
    {
        $orderRoute->update($request->validated());
        return self::Success(new OrderRouteResource($orderRoute), 'تم تعديل المسار بنجاح');
    }

    public function destroy(OrdertRoute $orderRoute)
    {
        $orderRoute->delete();
        return self::Success(null, 'تم حذف المسار بنجاح');
    }

    public function show(OrdertRoute $orderRoute)
    {
        return self::Success(new OrderRouteResource($orderRoute), 'تم جلب تفاصيل المسار بنجاح');
    }

    public function updateStatus(OrdertRoute $route, int $status)
    {
        if (!OrderTrackingStatus::tryFrom($status)) {
            return ResponseTrait::Error(null, 'الحالة غير صالحة', 422);
        }

        $route->update(['status' => $status]);

        return ResponseTrait::Success($route->refresh(), 'تم تحديث حالة التتبع بنجاح');
    }
}
