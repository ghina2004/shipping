<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipmentSupplierRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'contact_email' => 'sometimes|required|email',
            'contact_phone' => 'nullable|digits_between:8,20',
            'sup_invoice' => 'nullable|file|mimes:pdf,jpg,png,docx',
            'visible_to_customer' => 'nullable|boolean',
        ];
    }
}
