<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'first_name'  => 'required|string|max:120',
            'second_name' => 'nullable|string|max:120',
            'third_name'  => 'nullable|string|max:120',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20|unique:users,phone',
            'password'    => 'required|string|min:8',
        ];
    }
}
