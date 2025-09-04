<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentSupplierRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'required|exists:shipments,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|digits_between:8,20',
            'sup_invoice' => 'required|file|mimes:pdf,jpg,png,docx',
            'visible_to_customer' => 'nullable|boolean',
        ];
    }
}
