<?php

namespace App\Http\Requests\Rout;

use Illuminate\Foundation\Http\FormRequest;

class OrderRouteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'tracking_link' => 'required|url',

        ];
    }
}
