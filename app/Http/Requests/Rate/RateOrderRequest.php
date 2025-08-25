<?php

namespace App\Http\Requests\Rate;

use Illuminate\Foundation\Http\FormRequest;

class RateOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'employee_rate' => 'required||numeric|between:1,5',
            'service_rate' => 'required||numeric|between:1,5',
            'comment' => 'nullable|string'
        ];
    }
}
