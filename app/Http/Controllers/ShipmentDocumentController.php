<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
    use App\Http\Requests\ShipmentDocumentRequest;
    use App\Http\Resources\ShipmentDocumentResource;
    use App\Models\ShipmentDocument;
    use App\Services\Shipment\ShipmentDocument\ShipmentDocumentService;
    use App\Traits\ResponseTrait;


class ShipmentDocumentController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ShipmentDocumentService $shipmentDocumentService) {}

    public function store(ShipmentDocumentRequest $request)
    {
        $document = $this->shipmentDocumentService->addShipmentDocument($request->validated());
        return self::Success(new ShipmentDocumentResource($document), __('Document uploaded successfully'));
    }

    public function update(ShipmentDocumentRequest $request, ShipmentDocument $document)
    {

        $document = $this->shipmentDocumentService->updateShipmentDocument( $document, $request->validated());
        return self::Success(new ShipmentDocumentResource($document), __('Document updated successfully'));
    }

    public function destroy(ShipmentDocument $document)
    {
        $this->shipmentDocumentService->deleteShipmentDocument($document);
        return self::Success([], __('Document deleted successfully'));
    }

    public function show(ShipmentDocument $document)
    {
        $document = $this->shipmentDocumentService->getShipmentDocument($document);
        return self::Success(new ShipmentDocumentResource($document), __('Document fetched successfully'));
    }


}
