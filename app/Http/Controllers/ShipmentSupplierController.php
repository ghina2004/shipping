<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentSupplierRequest;
use App\Http\Requests\UpdateShipmentSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\ShipmentDocumentResource;
use App\Models\Shipment;
use App\Services\Shipment\ShipmentSupplierManager;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShipmentSupplierController extends Controller
{
    use ResponseTrait;

    public function __construct(private ShipmentSupplierManager $service)
    {
    }

    public function store(StoreShipmentSupplierRequest $request, Shipment $shipment)
    {

        $validated = $request->validated();
        $supplierData = collect($validated)->only(['name', 'address', 'contact_email', 'contact_phone'])->toArray();
        $invoiceFile = $request->file('sup_invoice');
        $shipment = $this->service->addSupplierAndInvoiceToShipment(
            $shipment,
            $supplierData,
            $invoiceFile,
            auth()->user()
        );

        return self::Success([
            'supplier' => new SupplierResource($shipment->shipmentSupplier),
            'invoice'  => new ShipmentDocumentResource(
                $shipment->shipmentDocuments->firstWhere('type', 'sup_invoice')
            ),
        ], __('Supplier and invoice added successfully.'));
    }


    public function show(Shipment $shipment)
    {
        $data = $this->service->showSupplierAndInvoice($shipment);

        return self::Success([
            'supplier' => $data['supplier'] ? new SupplierResource($data['supplier']) : null,
            'invoice'  => $data['invoice'] ? new ShipmentDocumentResource($data['invoice']) : null,
        ], __('Success'));
    }


    public function update(UpdateShipmentSupplierRequest $request, Shipment $shipment)
    {
        $validated = $request->validated();
        $supplierData = collect($validated)->only(['name', 'address', 'contact_email', 'contact_phone'])->toArray();
        $invoiceFile = $request->file('sup_invoice');
        $shipment = $this->service->updateSupplierAndInvoice(
            $shipment,
            $supplierData,
            $invoiceFile

        );

        return self::Success([
            'supplier' => new SupplierResource($shipment->shipmentSupplier),
            'invoice'  => new ShipmentDocumentResource(
                $shipment->shipmentDocuments->firstWhere('type', 'sup_invoice')
            ),
        ], __('Supplier and invoice updated successfully.'));
    }


    public function destroy(Shipment $shipment)
    {
        $this->service->deleteSupplierAndInvoice($shipment);

        return self::Success([], __('Supplier and invoice deleted successfully.'));
    }
}
