<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OriginalShippingCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|digits_between:8,20',
            'address' => 'required|string|max:255',
        ];
    }
}
