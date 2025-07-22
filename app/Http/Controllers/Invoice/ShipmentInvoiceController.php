<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\ShipmentInvoice;
use App\Services\Invoice\ShipmentInvoiceService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentInvoiceController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ShipmentInvoiceService $invoiceService) {}

    public function create(InvoiceRequest $request, int $shipmentId): JsonResponse
    {
        $invoice = $this->invoiceService->createInvoice($request->validated(), $shipmentId);

        return self::Success([
            'invoice' => new InvoiceResource($invoice)
        ], 'Invoice created successfully');
    }

    public function show(int $invoiceId): JsonResponse
    {
        $invoice = $this->invoiceService->showInvoice($invoiceId);

        return self::Success([
            'invoice' => new InvoiceResource($invoice)
        ],'invoice shown successfully');
    }

    public function update(InvoiceRequest $request, ShipmentInvoice $invoice): JsonResponse
    {
        $invoice = $this->invoiceService->updateInvoice($invoice, $request->validated());

        return self::Success([
            'invoice' => new InvoiceResource($invoice)
        ], 'Invoice updated successfully');
    }

    public function delete(ShipmentInvoice $invoice): JsonResponse
    {
        $this->invoiceService->deleteInvoice($invoice);

        return self::Success([], 'Invoice deleted successfully');
    }

    public function download(ShipmentInvoice $invoice): \Illuminate\Http\Response
    {
        return $this->invoiceService->downloadAsPdf($invoice);
    }
}
