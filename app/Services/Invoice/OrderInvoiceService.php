<?php

namespace App\Services\Invoice;

use App\Enums\Invoice\InvoiceType;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\ShipmentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;


class OrderInvoiceService
{
    public function createOrderInvoice(Order $order): OrderInvoice
    {
        $shipmentInvoices = ShipmentInvoice::whereIn('shipment_id', $order->shipments->pluck('id'))->get();

        $data = [
            'order_id' => $order->id,
            'invoice_number' => $this->generateOrderInvoiceNumber(),
            'total_initial_amount' => $shipmentInvoices->sum('initial_amount'),
            'total_customs_fee' => $shipmentInvoices->sum('customs_fee'),
            'total_service_fee' => $shipmentInvoices->sum('service_fee'),
            'total_company_profit' => $shipmentInvoices->sum('company_profit'),
            'total_final_amount' => $shipmentInvoices->sum('final_amount'),
            'notes' => 'فاتورة مجمعة للطلب #' . $order->id,
        ];

        return OrderInvoice::query()->create($data);
    }

    public function showInvoice(int $invoiceId): ShipmentInvoice
    {
        return OrderInvoice::query()->findOrFail($invoiceId);
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

    private function generateOrderInvoiceNumber(): int
    {
        $last = OrderInvoice::max('invoice_number') ?? 50000;
        return $last + 1;
    }
}
