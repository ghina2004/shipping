<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShipmentFullRequest;
use App\Http\Resources\ShipmentFullResource;
use App\Models\Shipment;
use App\Services\Answer\ShipmentAnswerService;
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
        protected ShipmentAnswerService $shipmentAnswerService
    ) {}

    public function show($shipmentId)
    {

        $shipment = $this->shipmentService->show($shipmentId);

        $shipment->loadMissing(['shipmentSupplier', 'answersShipment']);

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


            $supplierData = collect($data)->only([
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

            return self::Success(new ShipmentFullResource($shipment), __('success'));
        });
    }


    public function delete($shipmentId)
    {
        $shipment = Shipment::with(['shipmentSupplier', 'answersShipment'])->findOrFail($shipmentId);


        foreach ($shipment->answersShipment as $answer) {
            $this->shipmentAnswerService->deleteAnswer($answer);
        }

        if ($shipment->shipmentSupplier) {
            $this->supplierService->delete($shipment->shipmentSupplier);
        }

        $this->shipmentService->delete($shipment);

        return self::Success([], __('deleted successfully'));
    }
}
