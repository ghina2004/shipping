<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\ShipmentFullRequest;
use App\Http\Resources\ShipmentFullResource;
use App\Models\Shipment;
use App\Services\Answer\ShipmentAnswerService;
use App\Services\Shipment\ShipmentDocument\ShipmentDocumentService;
use App\Services\Shipment\ShipmentService;
use App\Services\Supplier\SupplierService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class ShipmentFullController extends Controller
{
    use ResponseTrait;
    public function __construct(
        protected ShipmentService $shipmentService,
        protected SupplierService $supplierService,
        protected ShipmentAnswerService $shipmentAnswerService,
        protected ShipmentDocumentService $shipmentDocumentService,
    ) {}

    public function show($shipmentId)
    {

        $shipment = $this->shipmentService->show($shipmentId);


        $shipment->loadMissing(['shipmentSupplier', 'answersShipment' ,'shipmentDocuments']);



        return self::Success(new ShipmentFullResource($shipment), __('success'));
    }


    public function update(ShipmentFullRequest $request, $shipmentId)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $shipmentId) {

            $shipmentData = collect($data)->only([
                'shipping_date', 'service_type', 'origin_country',
                'destination_country', 'shipping_method', 'cargo_weight',
                'containers_size', 'containers_numbers', 'employee_notes',
                'customer_notes', 'is_information_complete', 'is_confirm',
            ])->toArray();


            $shipment = $this->shipmentService->update( $shipmentData, $shipmentId);


            $supplierData = collect($data['supplier'] ?? [])->only([
                'name', 'address', 'contact_email', 'contact_phone'
            ])->toArray();

            if ($shipment->shipmentSupplier && !empty($supplierData)) {
                $this->supplierService->update($shipment->shipmentSupplier, $supplierData);
            }


            if (!empty($data['answers'])) {
                foreach ($data['answers'] as $answerData) {
                    $answer = $shipment->answersShipment->firstWhere('id', $answerData['id']);
                    if ($answer) {
                        $this->shipmentAnswerService->updateAnswer($answer, [
                            'answer' => $answerData['answer'] ?? $answer->answer,
                        ]);
                    }
                }
            }
            if (!empty($data['sup_invoice'])) {
                $shipment->load('shipmentDocuments');
                $existingDocument = $shipment->shipmentDocuments->firstWhere('type', 'sup_invoice');

                if ($existingDocument) {

                    $this->shipmentDocumentService->updateShipmentDocument($existingDocument, $data['sup_invoice']
                    );
                }
            }
            $shipment->load(['shipmentSupplier', 'answersShipment', 'shipmentDocuments']);


            return self::Success(new ShipmentFullResource($shipment), __('success'));
        });
    }


    public function delete($shipmentId)
    {
        $shipment = Shipment::with(['shipmentSupplier', 'answersShipment' ,'shipmentDocuments'])->findOrFail($shipmentId);


        foreach ($shipment->answersShipment as $answer) {
            $this->shipmentAnswerService->deleteAnswer($answer);
        }

        if ($shipment->shipmentSupplier) {
            $this->supplierService->delete($shipment->shipmentSupplier);
        }
        if ($shipment->shipmentDocuments) {
            foreach ($shipment->shipmentDocuments as $document) {
                $this->shipmentDocumentService->deleteShipmentDocument($document);
            }
        }

        $this->shipmentService->delete($shipment);

        return self::Success([], __('deleted successfully'));
    }
}
