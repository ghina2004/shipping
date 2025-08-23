<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentTrackingLogRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'exists:shipments,id',
            'location' => 'string|max:1000',
        ];
    }
}
