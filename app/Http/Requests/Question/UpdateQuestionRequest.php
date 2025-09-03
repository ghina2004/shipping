<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question_en' => 'sometimes|required|string|max:255',
            'question_ar' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:text,textarea,number,date,select,radio,checkbox,file,image,document',
            'category_id' => 'sometimes|array',
            'category_id.*' => 'exists:categories,id',
            'options' => 'nullable|array',
            'options.*.value_ar' => 'required_with:options|string|max:255',
            'options.*.value_en' => 'required_with:options|string|max:255',
        ];
    }
}
