<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Answer\StoreShipmentAnswersRequest;
use App\Http\Requests\Answer\UpdateShipmentAnswerRequest;
use App\Http\Resources\ShipmentAnswerResource;
use App\Models\ShipmentAnswer;
use App\Services\Answer\ShipmentAnswerService;
use App\Traits\ResponseTrait;

class ShipmentAnswerController extends Controller
{    use ResponseTrait;
    public function __construct(protected ShipmentAnswerService $shipmentAnswerService) {}

    public function store(StoreShipmentAnswersRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        $shipmentId = $validated['shipment_id'];
        $answers = $validated['answers'];

        $savedAnswers = $this->shipmentAnswerService->storeAnswers($shipmentId, $user, $answers);

        return self::success([
            'data' => ShipmentAnswerResource::collection($savedAnswers),
        ], __('Shipment answers saved successfully.'));
    }

    public function show(ShipmentAnswer $shipmentAnswer)
    {
        return self::Success(
            new ShipmentAnswerResource($shipmentAnswer),
            __('Shipment answer retrieved successfully.')
        );
    }

    public function update(UpdateShipmentAnswerRequest $request, ShipmentAnswer $shipmentAnswer)
    {
        $updated = $this->shipmentAnswerService->updateAnswer($shipmentAnswer, $request->validated());

        return self::Success(
            new ShipmentAnswerResource($updated),
            __('Shipment answer updated successfully.')
        );
    }

    public function destroy(ShipmentAnswer $shipmentAnswer)
    {
        $this->shipmentAnswerService->deleteAnswer($shipmentAnswer);

        return self::Success([], __('Shipment answer deleted successfully.'));
    }
}
