<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShipmentFullRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_date' => ['sometimes', 'date'],
            'service_type' => ['sometimes', Rule::in(['import', 'export'])],
            'origin_country' => ['sometimes', 'string'],
            'destination_country' => ['sometimes', 'string'],
            'shipping_method' => ['sometimes', Rule::in(['Land','sea' ,'air'])],
            'cargo_weight' => ['sometimes', 'integer'],
            'containers_size' => ['sometimes', 'integer'],
            'containers_numbers' => ['sometimes', 'integer'],
            'employee_notes' => ['sometimes', 'string', 'nullable'],
            'customer_notes' => ['sometimes', 'string', 'nullable'],
            'is_information_complete' => ['sometimes', 'boolean'],
            'is_confirm' => ['sometimes', 'boolean'],

            // Supplier fields
            'name' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'contact_email' => ['sometimes', 'email'],
            'contact_phone' => ['sometimes', 'string'],

            // Answers (array of shipment answer updates)
            'answers' => ['sometimes', 'array'],
            'answers.*.id' => ['required_with:answers.*', 'exists:shipment_answers,id'],
            'answers.*.answer' => ['sometimes', 'string'],

            //document
            'lab_invoice' => 'sometimes|file|mimes:pdf,jpg,png,docx',
        ];

    }
}
