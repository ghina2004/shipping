<?php

namespace App\Services\Invoice;

use App\Exceptions\Types\CustomException;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\ShipmentInvoice;
use App\Services\Payment\PaymentInfoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderInvoiceService
{
    public function __construct(protected PaymentInfoService $infoService) {}

    public function createOrderInvoice(Order $order): OrderInvoice
    {
        $shipmentInvoices = $this->getShipmentInvoices($order);

        return OrderInvoice::create([
            'order_id' => $order->id,
            'invoice_number' => $this->generateOrderInvoiceNumber(),
            'total_initial_amount' => $shipmentInvoices->sum('initial_amount'),
            'total_customs_fee' => $shipmentInvoices->sum('customs_fee'),
            'total_service_fee' => $shipmentInvoices->sum('service_fee'),
            'total_company_profit' => $shipmentInvoices->sum('company_profit'),
            'total_final_amount' => $shipmentInvoices->sum('final_amount'),
            'notes' => $this->buildInvoiceNotes($order),
        ]);

    }

    public function recalcIfExists(Order $order): ?OrderInvoice
    {
        return DB::transaction(function () use ($order) {
            $invoice = $order->orderInvoice()->lockForUpdate()->first();

            if (! $invoice) {
                return null;
            }

            $sum = $this->sumShipmentInvoices($order);

            $invoice->update([
                'total_initial_amount' => $sum['initial'],
                'total_customs_fee'    => $sum['customs'],
                'total_service_fee'    => $sum['service'],
                'total_company_profit' => $sum['profit'],
                'total_final_amount'   => $sum['final'],
                'notes'                => $this->buildInvoiceNotes($order),
            ]);

            $this->infoService->syncAfterOrderInvoiceChange($invoice);

            return $invoice->refresh();
        });
    }

    public function show(Order $order)
    {
        $invoice = $order->orderInvoice;
        if(!$invoice) throw new CustomException('there is no invoice yet' , 404);
        return $invoice;
    }

    private function sumShipmentInvoices(Order $order): array
    {
        $shipmentIds = $order->shipments()->pluck('id');

        $row = ShipmentInvoice::query()
            ->whereIn('shipment_id', $shipmentIds)
            ->selectRaw('
                COALESCE(SUM(initial_amount),0)  as s_initial,
                COALESCE(SUM(customs_fee),0)     as s_customs,
                COALESCE(SUM(service_fee),0)     as s_service,
                COALESCE(SUM(company_profit),0)  as s_profit,
                COALESCE(SUM(final_amount),0)    as s_final
            ')->first();

        return [
            'initial' => (float) $row->s_initial,
            'customs' => (float) $row->s_customs,
            'service' => (float) $row->s_service,
            'profit'  => (float) $row->s_profit,
            'final'   => (float) $row->s_final,
        ];
    }

    public function deleteInvoice(OrderInvoice $invoice): void
    {
        $invoice->delete();
    }

    public function downloadAsPdf(OrderInvoice $invoice): Response
    {
        $invoice->load('order.shipments');

        $pdf = Pdf::loadView('invoice.order_invoice', [
            'invoice' => $invoice
        ]);

        return $pdf->download('order_invoice_' . $invoice->invoice_number . '.pdf');
    }

    private function getShipmentInvoices(Order $order)
    {
        return ShipmentInvoice::whereIn(
            'shipment_id',
            $order->shipments->pluck('id')
        )->get();
    }

    private function generateOrderInvoiceNumber(): int
    {
        return (OrderInvoice::max('invoice_number') ?? 50000) + 1;
    }

    private function buildInvoiceNotes(Order $order): string
    {
        return 'order #' . $order->order_number;
    }
}
