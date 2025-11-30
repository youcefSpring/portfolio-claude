<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public can apply
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'cv_file' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'cover_letter' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'cv_file' => 'CV/resume',
            'cover_letter' => 'cover letter',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cv_file.required' => 'Please upload your CV or resume.',
            'cv_file.mimes' => 'The CV must be a file of type: PDF, DOC, or DOCX.',
            'cv_file.max' => 'The CV file size must not exceed 5MB.',
        ];
    }
}
