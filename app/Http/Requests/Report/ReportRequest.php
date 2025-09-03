<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // عدّليها حسب صلاحياتك
    }

    public function rules(): array
    {
        return [
            'period' => ['required', Rule::in(['weekly','monthly','yearly'])],
            'year'   => ['required','integer','between:2024,2025'],
            'month'  => ['required_if:period,monthly','nullable','integer','between:1,12'],
            'week'   => ['required_if:period,weekly','nullable','integer','between:1,53'],
            'shipping_manager_id' => ['nullable','integer','exists:users,id'],
        ];
    }

    public function filters(): array
    {
        return [
            'period' => $this->string('period')->lower(),
            'year'   => (int)$this->input('year'),
            'month'  => $this->filled('month') ? (int)$this->input('month') : null,
            'week'   => $this->filled('week') ? (int)$this->input('week') : null,
            'shipping_manager_id' => $this->input('shipping_manager_id'),
        ];
    }
}
