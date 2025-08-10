<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderInvoice;
use Illuminate\Database\Seeder;

class OrderInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $nextNumber = (int) (OrderInvoice::max('invoice_number') ?? 50000) + 1;

        Order::query()
            ->where('status', 1)
            ->with(['shipments.invoice'])
            ->chunkById(100, function ($orders) use (&$nextNumber) {
                foreach ($orders as $order) {
                    $shipmentInvoices = $order->shipments
                        ->pluck('invoice')
                        ->filter();

                    if ($shipmentInvoices->isEmpty()) {
                        continue;
                    }

                    OrderInvoice::where('order_id', $order->id)->delete();

                    OrderInvoice::create([
                        'order_id'             => $order->id,
                        'invoice_number'       => $nextNumber++,
                        'total_initial_amount' => $this->sum($shipmentInvoices, 'initial_amount'),
                        'total_customs_fee'    => $this->sum($shipmentInvoices, 'customs_fee'),
                        'total_service_fee'    => $this->sum($shipmentInvoices, 'service_fee'),
                        'total_company_profit' => $this->sum($shipmentInvoices, 'company_profit'),
                        'total_final_amount'   => $this->sum($shipmentInvoices, 'final_amount'),
                        'notes'                => 'فاتورة مجمعة للطلب #'.$order->order_number,
                    ]);
                }
            });
    }

    private function sum($collection, string $key): float
    {
        return round((float) $collection->sum($key), 2);
    }
}
