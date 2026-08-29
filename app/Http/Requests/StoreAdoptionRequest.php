<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:30', 'max:3000'],
            'proposed_improvements' => ['required', 'string', 'min:30', 'max:3000'],
            'recovery_plan' => ['required', 'string', 'min:50', 'max:5000'],
            'expected_completion_date' => ['required', 'date', 'after:today'],
            'relevant_skills' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
