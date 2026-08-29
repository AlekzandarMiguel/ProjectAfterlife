<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'version_number' => ['required', 'string', 'regex:/^v?[0-9]+\.[0-9]+(\.[0-9]+)?(-[a-zA-Z0-9]+)?$/'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'release_notes' => ['required', 'string', 'min:20'],
            'source_zip' => ['nullable', 'file', 'mimes:zip,tar,gz,7z,rar', 'max:51200'],
            'is_final_release' => ['nullable', 'boolean'],
        ];
    }
}
