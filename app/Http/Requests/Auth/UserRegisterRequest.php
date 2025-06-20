<?php

namespace App\Http\Requests\Auth;

use App\Http\Responses\Response;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'third_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'phone' => [
                'required',
                'regex:/^(\+|00)[0-9]{6,15}$/'
            ],
            'password' => 'required|string|min:8',
        ];
    }

}

