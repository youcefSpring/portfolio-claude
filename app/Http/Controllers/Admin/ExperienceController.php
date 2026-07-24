<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    /**
     * Display all experience entries.
     */
    public function index(): View
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    /**
     * Store a new experience entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = auth()->id();

        Experience::create($data);

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience entry created successfully.');
    }

    /**
     * Update an experience entry.
     */
    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->validated($request));

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience entry updated successfully.');
    }

    /**
     * Delete an experience entry.
     */
    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')
            ->with('success', 'Experience entry deleted successfully.');
    }

    /**
     * Shared validation. A current role has no end date.
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'in:full-time,part-time,contract,freelance,internship'],
            'description' => ['required', 'string', 'max:5000'],
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
