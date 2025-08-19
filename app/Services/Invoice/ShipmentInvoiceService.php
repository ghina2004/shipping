<?php

namespace App\Services\Invoice;

use App\Enums\Invoice\InvoiceType;
use App\Exceptions\Types\CustomException;
use App\Models\Shipment;
use App\Models\ShipmentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ShipmentInvoiceService
{
    public function __construct(protected OrderInvoiceService $orderInvoiceService) {}

    /**
     * @throws CustomException
     */
    public function createInvoice(array $data, Shipment $shipment): ShipmentInvoice
    {
        $this->ensureNoExistingInvoice($shipment->id);

        $data['shipment_id'] = $shipment->id;
        $data['invoice_number'] = $this->generateInvoiceNumber();
        $data['invoice_type'] = InvoiceType::from($data['invoice_type']);

        return ShipmentInvoice::create($data);
    }

    public function showInvoice(Shipment $shipment): ShipmentInvoice
    {
        $invoice = $shipment->invoice;
        if(!$invoice) throw new CustomException('there is no invoice yet',404);
        return $invoice;
    }

    public function updateInvoice(ShipmentInvoice $invoice, array $data): ShipmentInvoice
    {
        if (isset($data['invoice_type'])) {
            $data['invoice_type'] = InvoiceType::from($data['invoice_type']);
        }

        $invoice->update($data);

        $order = $invoice->shipment->shipmentOrder;
        $this->orderInvoiceService->recalcIfExists($order);

        return $invoice->refresh();
    }

    public function deleteInvoice(ShipmentInvoice $invoice): void
    {
        $invoice->delete();
    }

    public function downloadAsPdf(ShipmentInvoice $invoice): Response
    {
        $invoice->load('shipment');

        $pdf = Pdf::loadView('invoice.shipment_invoice', [
            'invoice' => $invoice
        ]);

        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }

    private function generateInvoiceNumber(): int
    {
        return (ShipmentInvoice::max('invoice_number') ?? 10000) + 1;
    }

    private function ensureNoExistingInvoice(int $shipmentId): void
    {
        if (ShipmentInvoice::where('shipment_id', $shipmentId)->exists()) {
            throw new CustomException(__('invoice.shipment_invoice_already_exists'), 422);
        }
    }
}
