<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRequest extends FormRequest
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
        return[
            'category_id' => 'exists:categories,id',
            'shipping_date' => 'date',
            'service_type' => 'string',
            'origin_country' => 'string|max:100',
            'destination_country' => 'string|max:100',
            'shipping_method' => 'string|max:100',
            'cargo_weight' => 'integer',
            'containers_size' => 'integer',
            'containers_numbers' => 'integer',
            'notes' => 'string',
            'status' => 'integer',
        ];
    }
}
