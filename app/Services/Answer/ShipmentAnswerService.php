<?php

namespace App\Services\Answer;

use App\Models\ShipmentAnswer;
use App\Services\Shipment\Collection;
use Illuminate\Support\Facades\DB;

class ShipmentAnswerService
{
    public function storeAnswers(int $shipmentId,  $user, array $answers)
    {
        return DB::transaction(function () use ($shipmentId, $user, $answers) {
            $savedAnswers = collect();

            foreach ($answers as $answer) {
                $savedAnswers->push(
                    ShipmentAnswer::create([
                        'shipment_id' => $shipmentId,
                        'shipment_question_id' => $answer['shipment_question_id'],
                        'answer' => $answer['answer'],
                        'user_id' => $user->id,
                    ])
                );
            }

            return $savedAnswers;
        });
}
    public function updateAnswer(ShipmentAnswer $shipmentAnswer, array $data): ShipmentAnswer
    {
        $shipmentAnswer->update($data);
        return $shipmentAnswer;
    }

    public function deleteAnswer(ShipmentAnswer $shipmentAnswer): void
    {
        $shipmentAnswer->delete();
    }

    public function show(ShipmentAnswer $shipmentAnswer)
    {
        return $shipmentAnswer;
    }





}
