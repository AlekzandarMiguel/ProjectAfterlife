<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinalReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'completion_summary' => ['required', 'string', 'min:50'],
            'completed_features' => ['required', 'string', 'min:50'],
            'testing_summary' => ['required', 'string', 'min:50'],
            'version_id' => ['nullable', 'exists:project_versions,id'],
        ];
    }
}
