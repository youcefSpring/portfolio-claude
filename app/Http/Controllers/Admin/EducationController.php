<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    /**
     * Display all education entries.
     */
    public function index(): View
    {
        $education = Education::orderBy('start_date', 'desc')->get();

        return view('admin.education.index', compact('education'));
    }

    /**
     * Store a new education entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = auth()->id();

        Education::create($data);

        return redirect()->route('admin.education.index')
            ->with('success', 'Education entry created successfully.');
    }

    /**
     * Update an education entry.
     */
    public function update(Request $request, Education $education): RedirectResponse
    {
        $education->update($this->validated($request));

        return redirect()->route('admin.education.index')
            ->with('success', 'Education entry updated successfully.');
    }

    /**
     * Delete an education entry.
     */
    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        return redirect()->route('admin.education.index')
            ->with('success', 'Education entry deleted successfully.');
    }

    /**
     * Shared validation. A current course of study has no end date.
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'degree' => ['required', 'string', 'max:255'],
            'field_of_study' => ['required', 'string', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $data['is_current'] = $request->boolean('is_current');

        if ($data['is_current']) {
            $data['end_date'] = null;
        }

        return $data;
    }
}
