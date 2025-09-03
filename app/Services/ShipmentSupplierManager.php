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

            return $shipment->fresh(['supplier', 'documents']);
        });
    }


    public function updateSupplierAndInvoice(
        Shipment $shipment,
        array $supplierData,
        UploadedFile $invoiceFile ,
        array $documentExtra = []
    ){
        return DB::transaction(function () use ($shipment, $supplierData, $invoiceFile, $documentExtra) {
            if (! $shipment->supplier) {
                throw ValidationException::withMessages([
                    'supplier' => ['Shipment has no supplier to update.']
                ]);
            }

            $this->supplierService->update($shipment->supplier, $supplierData);

            if ($invoiceFile) {
                $document = $shipment->documents()->where('type', 'sup_invoice')->first();
                if ($document) {
                    $this->documentService->updateShipmentDocument($document, $invoiceFile);
                    // Optionally update visible_to_customer or other flags:
                    if (isset($documentExtra['visible_to_customer'])) {
                        $document->update(['visible_to_customer' => (bool)$documentExtra['visible_to_customer']]);
                    }
                } else {
                    $docPayload = array_merge([
                        'shipment_id' => $shipment->id,
                        'type' => $documentExtra['type'] ?? 'sup_invoice',
                        'sup_invoice' => $invoiceFile,
                    ], $documentExtra);
                    $this->documentService->addShipmentDocument($docPayload);
                }
            }

            return $shipment->fresh(['supplier', 'documents']);
        });
    }

    public function deleteSupplierAndInvoice(Shipment $shipment): void
    {
        DB::transaction(function () use ($shipment) {
            // delete documents of type sup_invoice
            $documents = $shipment->documents()->where('type', 'sup_invoice')->get();
            foreach ($documents as $document) {
                $this->documentService->deleteShipmentDocument($document);
            }

            if ($shipment->supplier) {
                $this->supplierService->delete($shipment->supplier);
            }

            $shipment->update([
                'supplier_id' => null,
                'having_supplier' => false,
            ]);
        });
    }


    public function showSupplierAndInvoice(Shipment $shipment): array
    {
        return [
            'supplier' => $shipment->supplier,
            'invoice' => $shipment->documents()->where('type', 'sup_invoice')->first(),
        ];
    }
}
