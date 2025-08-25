<?php

namespace App\Http\Requests\Status;

use App\Enums\Status\OrderPaymentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderPaymentStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(OrderPaymentStatusEnum::cases())],
        ];
    }
}
