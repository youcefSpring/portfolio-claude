<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isTeacher());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'course_code' => ['nullable', 'string', 'max:50'],
            'credits' => ['nullable', 'integer', 'min:0', 'max:20'],
            'slug' => ['nullable', 'string', 'unique:courses,slug'],
            'description' => ['required', 'string', 'max:5000'],
            'content' => ['nullable', 'string', 'max:10000'],
            'objectives' => ['nullable', 'string', 'max:5000'],
            'learning_objectives' => ['nullable', 'string', 'max:5000'],
            'prerequisites' => ['nullable', 'string', 'max:5000'],
            'syllabus_content' => ['nullable', 'string', 'max:10000'],
            'assessment_methods' => ['nullable', 'string', 'max:5000'],
            'resources' => ['nullable', 'string', 'max:5000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'level' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'semester' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'instructor' => ['nullable', 'string', 'max:255'],
            'syllabus_file' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['nullable', 'in:active,archived,draft,upcoming,completed'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get the custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'title.max' => 'Course title cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'description.required' => 'Course description is required.',
            'description.max' => 'Course description cannot exceed 5000 characters.',
            'syllabus_file.file' => 'Syllabus must be a file.',
            'syllabus_file.mimes' => 'Syllabus must be a PDF file.',
            'syllabus_file.max' => 'Syllabus file must be smaller than 10MB.',
            'start_date.date' => 'Please provide a valid start date.',
            'end_date.date' => 'Please provide a valid end date.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            'status.in' => 'Invalid course status selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->title) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title),
            ]);
        }
    }
}