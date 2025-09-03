<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'first_name'  => ['required','string','max:120'],
            'second_name' => ['required','nullable','string','max:120'],
            'third_name'  => ['required','nullable','string','max:120'],
            'email'       => ['required','email','max:190','unique:users,email,'.$id],
            'phone'       => ['required','string','max:20','unique:users,phone,'.$id],
        ];
    }
}
