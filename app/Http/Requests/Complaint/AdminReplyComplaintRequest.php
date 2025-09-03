<?php

namespace App\Http\Requests\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class AdminReplyComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array {
        return [
            'admin_reply' => ['required','string','max:10000'],
        ];
    }
}
