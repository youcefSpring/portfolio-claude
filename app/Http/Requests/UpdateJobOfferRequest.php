<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isTeacher());
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
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'project_type' => ['required', 'in:consulting,freelance,contract,internship'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'duration' => ['nullable', 'string', 'max:100'],
            'location_type' => ['required', 'in:remote,on-site,hybrid'],
            'location' => ['nullable', 'string', 'max:255'],
            'skills_required' => ['nullable', 'array'],
            'skills_required.*' => ['string'],
            'status' => ['required', 'in:active,filled,cancelled'],
            'featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'budget_min' => 'minimum budget',
            'budget_max' => 'maximum budget',
            'location_type' => 'location type',
            'skills_required' => 'required skills',
            'skill_ids' => 'skills',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'budget_max.gte' => 'The maximum budget must be greater than or equal to the minimum budget.',
        ];
    }
}
