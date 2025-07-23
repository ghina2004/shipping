<?php

namespace App\Services\Invoice;

use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\ShipmentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class OrderInvoiceService
{
    public function createOrderInvoice(Order $order): OrderInvoice
    {
        $this->deleteExistingInvoice($order);

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

    private function deleteExistingInvoice(Order $order): void
    {
        OrderInvoice::where('order_id', $order->id)->delete();
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
        return 'فاتورة مجمعة للطلب #' . $order->order_number;
    }
}
