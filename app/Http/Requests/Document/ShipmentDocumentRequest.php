<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'shipment_id' => 'required|exists:shipments,id',
            'sup_invoice' => 'required|file|mimes:pdf,jpg,png,docx',
        ];
    }
}
