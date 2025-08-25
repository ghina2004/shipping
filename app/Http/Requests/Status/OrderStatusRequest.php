<?php

namespace App\Http\Requests\Status;

use App\Enums\Status\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(OrderStatusEnum::cases())],
        ];
    }
}
