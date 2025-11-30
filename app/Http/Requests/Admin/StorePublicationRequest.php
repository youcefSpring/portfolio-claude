<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:500'],
            'authors' => ['required', 'string', 'max:500'],
            'type' => ['required', 'in:journal,conference,book,book_chapter,thesis,report,preprint'],
            'status' => ['required', 'in:published,accepted,under_review,in_preparation'],
            'journal_name' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'volume' => ['nullable', 'string', 'max:50'],
            'issue' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 5)],
            'doi' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:500'],
            'abstract' => ['nullable', 'string', 'max:3000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
            'publication_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id']
        ];
    }

    /**
     * Get the custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Publication title is required.',
            'title.max' => 'Publication title cannot exceed 500 characters.',
            'authors.required' => 'Authors field is required.',
            'authors.max' => 'Authors field cannot exceed 500 characters.',
            'type.required' => 'Publication type is required.',
            'type.in' => 'Please select a valid publication type.',
            'status.required' => 'Publication status is required.',
            'status.in' => 'Please select a valid publication status.',
            'journal_name.max' => 'Journal name cannot exceed 255 characters.',
            'venue.max' => 'Venue name cannot exceed 255 characters.',
            'volume.max' => 'Volume cannot exceed 50 characters.',
            'issue.max' => 'Issue cannot exceed 50 characters.',
            'pages.max' => 'Pages cannot exceed 50 characters.',
            'year.required' => 'Publication year is required.',
            'year.integer' => 'Publication year must be a valid year.',
            'year.min' => 'Publication year must be 1900 or later.',
            'year.max' => 'Publication year cannot be in the future.',
            'doi.max' => 'DOI cannot exceed 255 characters.',
            'url.url' => 'Publication URL must be a valid URL.',
            'url.max' => 'Publication URL cannot exceed 500 characters.',
            'abstract.max' => 'Abstract cannot exceed 3000 characters.',
            'keywords.max' => 'Keywords cannot exceed 500 characters.',
            'description.max' => 'Description cannot exceed 5000 characters.',
            'publication_file.file' => 'Publication file must be a file.',
            'publication_file.mimes' => 'Publication file must be a PDF.',
            'publication_file.max' => 'Publication file must be smaller than 20MB.',
            'tags.*.exists' => 'One or more selected tags do not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'publication_file' => 'publication PDF',
            'external_link' => 'external link',
        ];
    }
}