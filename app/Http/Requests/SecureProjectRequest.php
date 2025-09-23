<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SecureProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'project_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\!\?]+$/', // Only allow safe characters
            ],
            'client_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,]+$/',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,]+$/',
            ],
            'description' => [
                'nullable',
                'string',
                'max:' . config('security.content.max_input_length', 10000),
            ],
            'info_project' => [
                'nullable',
                'string',
                'max:' . config('security.content.max_input_length', 10000),
            ],
            'summary_description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'project_category' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\_]+$/',
            ],
            'category_lookup_id' => [
                'nullable',
                'integer',
                'exists:lookup_data,id',
            ],
            'url_project' => [
                'nullable',
                'url',
                'max:255',
            ],
            'slug_project' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('project', 'slug_project')->ignore($this->route('id')),
            ],
            'images' => [
                'nullable',
                'array',
                'max:10', // Maximum 10 images
            ],
            'images.*' => [
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:' . config('security.upload.max_size', 2048),
                'dimensions:max_width=2048,max_height=2048',
            ],
            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:' . config('security.upload.max_size', 2048),
                'dimensions:max_width=2048,max_height=2048',
            ],
            'other_projects' => [
                'nullable',
                'array',
                'max:5', // Maximum 5 related projects
            ],
            'other_projects.*' => [
                'integer',
                'exists:project,id_project',
            ],
            'technologies_used' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[a-zA-Z0-9\s\-\_\.\,\+\#]+$/', // Allow common tech characters
            ],
            'project_duration' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\_]+$/',
            ],
            'project_year' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . (date('Y') + 1),
            ],
            'status' => [
                'required',
                Rule::in(['Active', 'Inactive', 'Draft']),
            ],
            'sequence' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],
            'is_featured' => [
                'nullable',
                'boolean',
            ],
            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'meta_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'meta_keywords' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'project_name.regex' => 'Project name contains invalid characters. Only letters, numbers, spaces, hyphens, underscores, dots, commas, and basic punctuation are allowed.',
            'client_name.regex' => 'Client name contains invalid characters. Only letters, numbers, spaces, hyphens, underscores, dots, and commas are allowed.',
            'location.regex' => 'Location contains invalid characters. Only letters, numbers, spaces, hyphens, underscores, dots, and commas are allowed.',
            'project_category.regex' => 'Project category contains invalid characters. Only letters, numbers, spaces, hyphens, and underscores are allowed.',
            'technologies_used.regex' => 'Technologies used contains invalid characters. Only letters, numbers, spaces, hyphens, underscores, dots, commas, plus signs, and hash symbols are allowed.',
            'project_duration.regex' => 'Project duration contains invalid characters. Only letters, numbers, spaces, hyphens, and underscores are allowed.',
            'images.max' => 'You can upload a maximum of 10 images.',
            'other_projects.max' => 'You can select a maximum of 5 related projects.',
            'images.*.dimensions' => 'Image dimensions cannot exceed 2048x2048 pixels.',
            'featured_image.dimensions' => 'Featured image dimensions cannot exceed 2048x2048 pixels.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Sanitize input data
        $this->merge([
            'project_name' => $this->sanitizeInput($this->project_name),
            'client_name' => $this->sanitizeInput($this->client_name),
            'location' => $this->sanitizeInput($this->location),
            'description' => $this->sanitizeHtml($this->description),
            'info_project' => $this->sanitizeHtml($this->info_project),
            'summary_description' => $this->sanitizeInput($this->summary_description),
            'project_category' => $this->sanitizeInput($this->project_category),
            'technologies_used' => $this->sanitizeInput($this->technologies_used),
            'project_duration' => $this->sanitizeInput($this->project_duration),
            'meta_title' => $this->sanitizeInput($this->meta_title),
            'meta_description' => $this->sanitizeInput($this->meta_description),
            'meta_keywords' => $this->sanitizeInput($this->meta_keywords),
        ]);
    }

    /**
     * Sanitize basic input
     *
     * @param string|null $input
     * @return string|null
     */
    protected function sanitizeInput(?string $input): ?string
    {
        if (is_null($input)) {
            return null;
        }

        // Remove null bytes and control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Trim whitespace
        $input = trim($input);

        return $input === '' ? null : $input;
    }

    /**
     * Sanitize HTML content
     *
     * @param string|null $input
     * @return string|null
     */
    protected function sanitizeHtml(?string $input): ?string
    {
        if (is_null($input)) {
            return null;
        }

        // Remove null bytes and control characters
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Allow basic HTML tags if HTML purifier is enabled
        if (config('security.content.enable_html_purifier', true)) {
            $allowedTags = config('security.content.allowed_html_tags', []);
            $input = strip_tags($input, '<' . implode('><', $allowedTags) . '>');
        } else {
            $input = strip_tags($input);
        }

        // Trim whitespace
        $input = trim($input);

        return $input === '' ? null : $input;
    }
}