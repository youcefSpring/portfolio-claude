<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::query()
            ->when(request('search'), function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('category'), function ($query, $category) {
                return $query->where('category', $category);
            })
            ->ordered()
            ->paginate(15);

        $categories = [
            'programming' => 'Programming Language',
            'framework' => 'Framework/Library',
            'database' => 'Database',
            'tool' => 'Tool/Software',
            'design' => 'Design',
            'soft_skill' => 'Soft Skill',
            'other' => 'Other'
        ];

        return view('admin.skills.index', compact('skills', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'programming' => 'Programming Language',
            'framework' => 'Framework/Library',
            'database' => 'Database',
            'tool' => 'Tool/Software',
            'design' => 'Design',
            'soft_skill' => 'Soft Skill',
            'other' => 'Other'
        ];

        return view('admin.skills.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'slug' => 'nullable|string|max:255|unique:skills,slug',
            'description' => 'nullable|string',
            'category' => 'nullable|in:programming,framework,database,tool,design,soft_skill,other',
            'proficiency_level' => 'nullable|integer|min:1|max:5',
            'icon' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg,webp|max:2048',
            'simple_icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'is_featured' => 'boolean',
            'years_experience' => 'nullable|integer|min:0|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['category'] = $validated['category'] ?? 'other';
        $validated['proficiency_level'] = $validated['proficiency_level'] ?? 3;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('images/skills', 'public');
        }

        Skill::create($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Skill $skill)
    {
        return view('admin.skills.show', compact('skill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Skill $skill)
    {
        $categories = [
            'programming' => 'Programming Language',
            'framework' => 'Framework/Library',
            'database' => 'Database',
            'tool' => 'Tool/Software',
            'design' => 'Design',
            'soft_skill' => 'Soft Skill',
            'other' => 'Other'
        ];

        return view('admin.skills.edit', compact('skill', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
            'slug' => 'nullable|string|max:255|unique:skills,slug,' . $skill->id,
            'description' => 'nullable|string',
            'category' => 'nullable|in:programming,framework,database,tool,design,soft_skill,other',
            'proficiency_level' => 'nullable|integer|min:1|max:5',
            'icon' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg,webp|max:2048',
            'simple_icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'is_featured' => 'boolean',
            'years_experience' => 'nullable|integer|min:0|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? $skill->sort_order;
        $validated['category'] = $validated['category'] ?? $skill->category ?? 'other';
        $validated['proficiency_level'] = $validated['proficiency_level'] ?? $skill->proficiency_level ?? 3;

        // Handle logo removal
        if ($request->boolean('remove_logo') && $skill->logo) {
            Storage::disk('public')->delete($skill->logo);
            $validated['logo'] = null;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($skill->logo) {
                Storage::disk('public')->delete($skill->logo);
            }
            $validated['logo'] = $request->file('logo')->store('images/skills', 'public');
        }

        $skill->update($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill)
    {
        // Delete logo if exists
        if ($skill->logo) {
            Storage::disk('public')->delete($skill->logo);
        }

        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill deleted successfully.');
    }
}
