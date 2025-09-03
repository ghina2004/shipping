<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOriginalShippingCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'contact_email' => 'email',
            'contact_phone' => 'digits_between:8,20',
            'address' => 'string|max:255',
        ];
    }
}
