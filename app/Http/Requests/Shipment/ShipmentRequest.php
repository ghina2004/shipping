<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'number' => 'required|integer',
            'shipping_date' => 'required|date',
            'service_type' => 'nullable|string',
            'origin_country' => 'nullable|string|max:100',
            'destination_country' => 'required|string|max:100',
            'shipping_method' => 'required|string|max:100',
            'cargo_weight' => 'nullable|integer',
            'containers_size' => 'nullable|integer',
            'containers_numbers' => 'nullable|integer',
            'notes' => 'nullable|string',
        ];
    }
}
