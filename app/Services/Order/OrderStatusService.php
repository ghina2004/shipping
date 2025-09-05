<?php

namespace App\Services\Order;

use App\Enums\Payment\PaymentDueDate;
use App\Enums\Status\OrderPaymentStatusEnum;
use App\Enums\Status\OrderStatusEnum;
use App\Models\Order;
use App\Exceptions\Types\CustomException;

class OrderStatusService
{
    public function changePaymentStatus(Order $order, OrderPaymentStatusEnum $status): Order
    {
        $order->update(['payment_status' => $status->value]);
        return $order;
    }

    public function changeOrderStatus(Order $order, OrderStatusEnum $status): Order
    {
        if ($status === OrderStatusEnum::Delivered) {
            $allDelivered = $order->shipments()
                    ->where('status', '!=', 'delivered')
                    ->count() === 0;

            if (!$allDelivered) {
                throw new CustomException('Not all shipments are delivered yet.');
            }

            $this->setRemainingPaymentDeadline($order, PaymentDueDate::DUE_DATE->value);
        }

        $order->update(['order_status' => $status->value]);

        return $order->fresh();
    }

    private function setRemainingPaymentDeadline(Order $order, int $days ): void
    {
        $invoice = $order->orderInvoice()->with('orderPayment')->first();
        if (!$invoice) {
            return;
        }

        $payment = $invoice->orderPayment;
        if (!$payment) {
            return;
        }

        $total = (float)$invoice->total_final_amount;
        $paid  = (float)$payment->paid_amount;
        $due   = round($total - $paid, 2);

        if ($due > 0) {
            $payment->update([
                'due_date' => now()->addDays($days)->toDateString(),
            ]);
        }
    }

    public function changeToCanConfirm(Order $order): ?Order
    {
        $order->update(['can_confirm'=>1]);
        return $order->fresh();
    }
}
