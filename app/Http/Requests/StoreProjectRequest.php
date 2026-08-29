<?php

namespace App\Http\Requests;

use App\Enums\DevelopmentStatus;
use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'short_description' => ['required', 'string', 'max:350'],
            'description' => ['required', 'string', 'min:50'],
            'category_id' => ['required', 'exists:categories,id'],
            'project_type' => ['required', new Enum(ProjectType::class)],
            'development_status' => ['required', new Enum(DevelopmentStatus::class)],
            'reason_for_abandonment' => ['required', 'string', 'min:20'],
            'original_development_date' => ['nullable', 'date'],
            'last_development_date' => ['nullable', 'date'],
            'technologies' => ['required', 'array', 'min:1'],
            'technologies.*' => ['exists:technologies,id'],
            'source_zip' => ['nullable', 'file', 'mimes:zip,tar,gz,7z,rar', 'max:51200'], // max 50MB
            'readme' => ['nullable', 'file', 'mimes:md,txt,pdf', 'max:10240'],
            'documentation' => ['nullable', 'file', 'mimes:pdf,docx,doc,md,zip', 'max:20480'],
            'database_sql' => ['nullable', 'file', 'mimes:sql,txt,dump,zip', 'max:20480'],
            'screenshots.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'ownership_confirmed' => ['required', 'accepted'],
        ];
    }
}
