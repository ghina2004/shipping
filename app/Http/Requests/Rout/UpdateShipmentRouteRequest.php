<?php

namespace App\Http\Requests\Rout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentRouteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'exists:shipments,id',
            'tracking_link' => 'url',
            'tracking_number'=>'string|max:10',
        ];
    }
}
