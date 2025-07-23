<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Resources\InvoiceOrderResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Order;
use App\Models\OrderInvoice;
use App\Models\ShipmentInvoice;
use App\Services\Invoice\OrderInvoiceService;
use App\Services\Invoice\ShipmentInvoiceService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderInvoiceController extends Controller
{
    use ResponseTrait;

    public function __construct(protected OrderInvoiceService $invoiceService) {}

    public function create(Order $order): JsonResponse
    {
        $invoice = $this->invoiceService->createOrderInvoice($order);

        return self::Success([
            'invoice' => new InvoiceOrderResource($invoice)
        ], 'Invoice created successfully');
    }

    public function show(OrderInvoice $invoice): JsonResponse
    {
        return self::Success([
            'invoice' => new InvoiceOrderResource($invoice)
        ],'invoice shown successfully');
    }

    public function delete(OrderInvoice $invoice): JsonResponse
    {
        $this->invoiceService->deleteInvoice($invoice);

        return self::Success([], 'Invoice deleted successfully');
    }

    public function download(OrderInvoice $invoice): \Illuminate\Http\Response
    {
        return $this->invoiceService->downloadAsPdf($invoice);
    }
}
