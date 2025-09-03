<?php

namespace App\Http\Requests\Status;

use App\Enums\Status\ShipmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShipmentStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(ShipmentStatusEnum::cases())],
        ];
    }
}
