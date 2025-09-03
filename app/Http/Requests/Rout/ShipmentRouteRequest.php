<?php

namespace App\Http\Requests\Rout;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentRouteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'required|exists:shipments,id',
            'tracking_link' => 'required|url',

        ];
    }
}
