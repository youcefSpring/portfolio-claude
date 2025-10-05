<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (
            auth()->user()->isTeacher() ||
            auth()->user()->isAdmin() ||
            auth()->user()->isEditor()
        );
    }

    public function rules(): array
    {
        $publicationId = $this->route('publication')->id;

        return [
            'title' => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'in:journal,conference,book,book_chapter,thesis,report,preprint'],
            'journal_name' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'volume' => ['nullable', 'string', 'max:50'],
            'issue' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'string', 'max:50'],
            'doi' => ['nullable', 'string', 'max:255', Rule::unique('publications', 'doi')->ignore($publicationId)],
            'isbn' => ['nullable', 'string', 'max:20'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'authors' => ['required', 'string', 'max:500'],
            'abstract' => ['nullable', 'string', 'max:2000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:published,accepted,under_review,in_preparation'],
            'publication_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The publication title is required.',
            'type.in' => 'Please select a valid publication type.',
            'year.max' => 'Publication year cannot be in the future.', 'year.min' => 'Publication year must be 1900 or later.',
            'doi.unique' => 'A publication with this DOI already exists.',
            'publication_file.mimes' => 'The publication file must be a PDF.',
            'publication_file.max' => 'The publication file must not exceed 20MB.'
        ];
    }
}