<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'category_id' => 'required|exists:categories,id',
            'shipping_date' => 'required|date',
            'service_type' => ['required', Rule::in(['import', 'export'])],
            'origin_country' => 'string|max:100',
            'destination_country' => 'required|string|max:100',
            'shipping_method' => ['required' , Rule::in(['Land','sea' ,'air'])],
            'cargo_weight' => 'integer',
            'containers_size' => 'integer',
            'containers_numbers' => 'integer',
            'employee_notes' => 'string|max:10000',
            'customer_notes' => 'string|max:10000',
        ];
    }
}
