<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\ShipmentInvoice;
use Illuminate\Database\Seeder;

class ShipmentInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $nextNumber = (int) (ShipmentInvoice::max('invoice_number') ?? 60000) + 1;

        Shipment::query()
            ->where('is_information_complete', 1)
            ->with('shipmentOrder')
            ->chunkById(200, function ($shipments) use (&$nextNumber) {
                foreach ($shipments as $shipment) {
                    if (ShipmentInvoice::where('shipment_id', $shipment->id)->exists()) {
                        continue;
                    }

                    $initial       = $this->round2(mt_rand(200, 800));
                    $customsFee    = $this->round2(mt_rand(0, 200));
                    $serviceFee    = $this->round2(mt_rand(20, 100));
                    $companyProfit = $this->round2(mt_rand(30, 150));
                    $final         = $this->round2($initial + $customsFee + $serviceFee + $companyProfit);

                    ShipmentInvoice::create([
                        'shipment_id'    => $shipment->id,
                        'invoice_number' => $nextNumber++,
                        'invoice_type'   => 'initial',
                        'initial_amount' => $initial,
                        'customs_fee'    => $customsFee,
                        'service_fee'    => $serviceFee,
                        'company_profit' => $companyProfit,
                        'final_amount'   => $final,
                        'notes'          => 'فاتورة شحنة رقم '.$shipment->number,
                    ]);
                }
            });
    }

    private function round2($v): float
    {
        return round((float) $v, 2);
    }
}
