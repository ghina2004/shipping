<?php

namespace App\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'first_name'  => ['required','string','max:120'],
            'second_name' => ['required','nullable','string','max:120'],
            'third_name'  => ['required','nullable','string','max:120'],
            'email'       => ['required','email','max:190','unique:users,email,'.$userId],
            'phone'       => ['required','string','max:20','unique:users,phone,'.$userId],
        ];
    }
}

