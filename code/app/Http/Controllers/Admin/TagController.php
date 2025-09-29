<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{


    public function index(Request $request): View
    {
        $query = Tag::withCount(['projects', 'blogPosts', 'publications']);

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $tags = $query->orderBy('name')->paginate(20);

        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create($request->validated());

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(StoreTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        if ($tag->projects()->count() > 0 || $tag->blogPosts()->count() > 0 || $tag->publications()->count() > 0) {
            return redirect()->route('admin.tags.index')
                ->with('error', 'Cannot delete tag that is still in use.');
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    public function bulkDeleteUnused(): RedirectResponse
    {
        // Find tags that are not used by any projects, blog posts, or publications
        $unusedTags = Tag::whereDoesntHave('projects')
            ->whereDoesntHave('blogPosts')
            ->whereDoesntHave('publications')
            ->get();

        $deletedCount = $unusedTags->count();

        if ($deletedCount > 0) {
            // Delete all unused tags
            Tag::whereDoesntHave('projects')
                ->whereDoesntHave('blogPosts')
                ->whereDoesntHave('publications')
                ->delete();

            return redirect()->route('admin.tags.index')
                ->with('success', "Successfully deleted {$deletedCount} unused tag(s).");
        }

        return redirect()->route('admin.tags.index')
            ->with('info', 'No unused tags found to delete.');
    }
}
