<?php

namespace App\Services\Rate;

use App\Enums\Status\OrderStatusEnum;
use App\Exceptions\Types\CustomException;
use App\Models\Order;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RateOrderService
{
    public function rate(Order $order, array $data): Rate
    {
        $user = Auth::user();

        if ((int) $order->customer_id !== (int) $user->id) {
            throw new CustomException('You are not allowed to rate this order.', 403);
        }

         if ($order->order_status !== OrderStatusEnum::Delivered->value) {
             throw new CustomException('You can rate only after the order is delivered.', 422);
         }

        return DB::transaction(function () use ($order, $user, $data) {
            $rate = Rate::query()->updateOrCreate(
                [
                    'customer_id' => $user->id,
                    'order_id'    => $order->id,
                ],
                [
                    'employee_id'   => $order->employee_id,
                    'service_rate'  => (int) $data['service_rate'],
                    'employee_rate' => (int) $data['employee_rate'],
                    'comment'       => $data['comment'] ?? null,
                ]
            );

            return $rate->refresh();
        });
    }

    public function showMyRate(Order $order): ?Rate
    {
        $user = Auth::user();

        return Rate::query()
            ->where('order_id', $order->id)
            ->where('customer_id', $user->id)
            ->first();
    }
}
