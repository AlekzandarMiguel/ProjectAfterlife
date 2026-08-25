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
            
            // Strict file security validation
            'source_zip' => [
                'nullable',
                'file',
                'mimes:zip,tar,gz',
                'mimetypes:application/zip,application/x-zip-compressed,application/x-tar,application/gzip,application/x-gzip',
                'max:51200' // Max 50MB
            ],
            'readme' => [
                'nullable',
                'file',
                'mimes:md,txt,pdf',
                'max:10240' // Max 10MB
            ],
            'documentation' => [
                'nullable',
                'file',
                'mimes:pdf,md,txt,zip',
                'max:20480' // Max 20MB
            ],
            'database_sql' => [
                'nullable',
                'file',
                'mimes:sql,txt,gz,zip',
                'max:30720' // Max 30MB
            ],
            'screenshots' => ['nullable', 'array', 'max:5'],
            'screenshots.*' => [
                'file',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:5120' // Max 5MB
            ],
            'ownership_confirmed' => ['required', 'accepted'],
        ];
    }
}
