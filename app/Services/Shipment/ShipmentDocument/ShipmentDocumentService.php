<?php

namespace App\Services\Shipment\ShipmentDocument;

use App\Helper\FileHelper;
use App\Models\ShipmentDocument;
use Illuminate\Http\UploadedFile;

class ShipmentDocumentService
{
    public function addShipmentDocument(array $data)
    {
        $filePath = FileHelper::upload($data['sup_invoice'], 'shipment_documents');

        return ShipmentDocument::create([
            'shipment_id' => $data['shipment_id'],
            'type' => $data['type'] ?? 'sup_invoice',
            'file_path' => $filePath,
            'uploaded_by' => auth()->id(),
            'visible_to_customer' => true,
        ]);
    }

    public function updateShipmentDocument(ShipmentDocument $document, UploadedFile $file): ShipmentDocument
    {

        FileHelper::delete($document->file_path);


        $filePath = FileHelper::upload($file, 'shipment_documents');

        $document->update([
            'file_path' => $filePath,
        ]);

        return $document;
    }
    public function deleteShipmentDocument(ShipmentDocument $document): void
    {

        FileHelper::delete($document->file_path);
        $document->delete();
    }

    public function getShipmentDocument(ShipmentDocument $document): ShipmentDocument
    {
        return $document ;
    }
}
