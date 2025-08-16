<?php

namespace App\Http\Requests\Payment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MyFatoorahVerifyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_id'      => ['required','integer','exists:orders,id'],
            'mf_invoice_id' => ['required_without:payment_id','string'],
            'payment_id'    => ['required_without:mf_invoice_id','string'],
        ];
    }


}

