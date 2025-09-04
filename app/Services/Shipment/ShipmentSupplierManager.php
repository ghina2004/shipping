<?php

namespace App\Services\Shipment;

use App\Models\Shipment;
use App\Models\Supplier;
use App\Models\User;
use App\Services\Supplier\SupplierService;
use App\Services\Shipment\ShipmentDocument\ShipmentDocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentSupplierManager
{

    public function __construct(protected SupplierService $supplierService, protected ShipmentDocumentService $documentService)
    {
    }

    public function addSupplierAndInvoiceToShipment(
        Shipment     $shipment,
        array        $supplierData,
        UploadedFile $invoiceFile,
        User         $manager
    ){
        if ($shipment->having_supplier) {
            throw ValidationException::withMessages([
                'shipment' => ['This shipment already has a supplier.']
            ]);
        }
        return DB::transaction(function () use ($shipment, $supplierData, $invoiceFile, $manager) {

            $supplier = $this->supplierService->create($supplierData, $manager);

            $shipment->update([
                'supplier_id' => $supplier->id,
            ]);

            $docPayload= $this->documentService->addShipmentDocument([
                'shipment_id' => $shipment->id,
                'type' => 'sup_invoice',
                'sup_invoice' => $invoiceFile,
            ]);


            $this->documentService->addShipmentDocument($docPayload);

            return $shipment->fresh(['shipmentSupplier', 'shipmentDocuments']);
        });
    }


    public function updateSupplierAndInvoice(
        Shipment $shipment,
        array $supplierData,
        UploadedFile $invoiceFile

    ){
        return DB::transaction(function () use ($shipment, $supplierData, $invoiceFile) {
            if (! $shipment->shipmentSupplier) {
                throw ValidationException::withMessages([
                    'supplier' => ['Shipment has no supplier to update.']
                ]);
            }

            $this->supplierService->update($shipment->shipmentSupplier, $supplierData);

            $shipment->load('shipmentDocuments');

            if ($invoiceFile) {
                $document = $shipment->shipmentDocuments
                    ->firstWhere('type', 'sup_invoice');
                if ($document) {
                    $this->documentService->updateShipmentDocument($document, $invoiceFile);

                } }


            return $shipment->fresh(['shipmentSupplier', 'shipmentDocuments']);
        });
    }

    public function deleteSupplierAndInvoice(Shipment $shipment): void
    {
        DB::transaction(function () use ($shipment) {

            $shipment->load('shipmentDocuments', 'shipmentSupplier');


            $documents = $shipment->shipmentDocuments->where('type', 'sup_invoice');
            foreach ($documents as $document) {
                $this->documentService->deleteShipmentDocument($document);
            }


            if ($shipment->shipmentSupplier) {
                $this->supplierService->delete($shipment->shipmentSupplier);
            }


            $shipment->update([
                'supplier_id' => null,
            ]);
        });
    }


    public function showSupplierAndInvoice(Shipment $shipment): array
    {

        $shipment->load('shipmentSupplier', 'shipmentDocuments');

        return [
            'supplier' => $shipment->shipmentSupplier,
            'invoice'  => $shipment->shipmentDocuments->firstWhere('type', 'sup_invoice'),
        ];
    }

}
