<?php

namespace App\Http\Requests\Contract;

use Illuminate\Foundation\Http\FormRequest;

class UploadGenericContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => ['sometimes','string','max:190'],
            'file'  => ['required','file','mimes:pdf,doc,docx'],
        ];
    }
}
