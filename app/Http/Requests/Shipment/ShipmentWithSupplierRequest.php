<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShipmentWithSupplierRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'shipping_date' => 'required|date',
            'service_type' => ['required', Rule::in(['import', 'export'])],
            'origin_country' => 'nullable|string|max:100',
            'destination_country' => 'required|string|max:100',
            'shipping_method' => ['required' , Rule::in(['Land','sea' ,'air'])],
            'cargo_weight' => 'required|integer',
            'containers_size' => 'nullable|integer',
            'containers_numbers' => 'nullable|integer',
            'customer_notes' => 'nullable|string|max:1000',
            'employee_notes'=> 'nullable|string|max:1000',
            'is_information_complete' => 'nullable|boolean',
            'is_confirm' => 'nullable|boolean',



            'having_supplier' => 'required|boolean',
            'supplier.name' => 'required_if:having_supplier,1|string|max:255',
            'supplier.address' => 'required_if:having_supplier,1|string|max:255',
            'supplier.contact_email' => 'required_if:having_supplier,1|email',
            'supplier.contact_phone' => 'required_if:having_supplier,1|numeric',


            'sup_invoice' => 'required_if:having_supplier,true|file|mimes:pdf,jpg,png,docx',
        ];
    }
}
