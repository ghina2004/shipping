<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array {
        return [
            'subject' => ['required','string','max:190'],
            'message' => ['required','string','max:10000'],
        ];
    }
}
