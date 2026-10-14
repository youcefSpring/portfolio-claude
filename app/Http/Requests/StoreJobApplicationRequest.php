<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    /**
     * Algerian phone number, separators already stripped:
     * mobile 05/06/07 + 8 digits, or landline 02/03/04 + 7 digits,
     * with a leading 0 or the +213 / 00213 country code.
     */
    public const ALGERIAN_PHONE_REGEX = '/^(?:\+213|00213|0)(?:[567]\d{8}|[234]\d{7})$/';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public can apply
    }

    /**
     * Normalize input before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => preg_replace('/[\s.\-()]/', '', (string) $this->phone),
            // Accept "@handle", "handle" or a t.me link; store the bare username
            'telegram' => $this->filled('telegram')
                ? preg_replace('#^(?:https?://)?(?:www\.)?(?:t\.me/|telegram\.me/)?@?#i', '', trim($this->telegram))
                : null,
        ]);
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
            'phone' => ['required', 'string', 'regex:' . self::ALGERIAN_PHONE_REGEX],
            'github_url' => ['nullable', 'url', 'max:500', 'regex:#^https?://(www\.)?github\.com/[A-Za-z0-9_.-]+/?#i'],
            'linkedin_url' => ['nullable', 'url', 'max:500', 'regex:#^https?://([a-z]{2,3}\.)?linkedin\.com/(in|pub|company)/[^/\s]+/?#i'],
            'telegram' => ['nullable', 'string', 'regex:/^[A-Za-z][A-Za-z0-9_]{4,31}$/'],
            'portfolio_url' => ['nullable', 'url', 'max:500'],
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
            'github_url' => 'GitHub profile',
            'linkedin_url' => 'LinkedIn profile',
            'telegram' => 'Telegram username',
            'portfolio_url' => 'portfolio link',
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
            'phone.regex' => 'Please enter a valid Algerian phone number (e.g. 0555 12 34 56 or +213 555 12 34 56).',
            'github_url.regex' => 'Please enter a GitHub profile link (e.g. https://github.com/username).',
            'linkedin_url.regex' => 'Please enter a LinkedIn profile link (e.g. https://linkedin.com/in/username).',
            'telegram.regex' => 'Please enter a valid Telegram username (5–32 letters, digits or underscores).',
            'cv_file.required' => 'Please upload your CV or resume.',
            'cv_file.mimes' => 'The CV must be a file of type: PDF, DOC, or DOCX.',
            'cv_file.max' => 'The CV file size must not exceed 5MB.',
        ];
    }
}
