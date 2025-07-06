<?php

namespace App\Http\Requests\Answer;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentAnswersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'required|exists:shipments,id',
            'answers'     => 'required|array',
            'answers.*.shipment_question_id' => 'required|exists:shipment_questions,id',
            'answers.*.answer' => 'required|string|max:1000',
        ];
    }
}
