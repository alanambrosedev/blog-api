<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
            // Ensure the category_id actually exists in the DB
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            // Image is optional, but if present must be a file type (jpeg, png, etc) max 2MB
            'image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
            // Validate tags array if provided
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'], // Check each tag ID exists
        ];
    }
}
