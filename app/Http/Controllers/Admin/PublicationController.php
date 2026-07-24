<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePublicationRequest;
use App\Http\Requests\Admin\UpdatePublicationRequest;
use App\Models\Publication;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{


    public function index(Request $request): View
    {
        $query = Publication::with(['user', 'tags']);

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('journal', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $publications = $query->orderBy('year', 'desc')->latest()->paginate(10);

        $tags = Tag::orderBy('name')->get();

        return view('admin.publications.index', compact('publications', 'tags'));
    }

    public function create(): View
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.publications.create', compact('tags'));
    }

    public function store(StorePublicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Map form field names to database field names
        if (isset($data['journal_name'])) {
            $data['journal'] = $data['journal_name'];
            unset($data['journal_name']);
        }

        if ($request->hasFile('publication_file')) {
            $data['publication_file_path'] = $this->storeFile($request->file('publication_file'));
        }

        $publication = Publication::create($data);

        if ($request->has('tags') && $request->tags) {
            $publication->tags()->attach($request->tags);
        }

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publication created successfully.');
    }

    public function show(Publication $publication): View
    {
        $publication->load(['user', 'tags']);
        return view('admin.publications.show', compact('publication'));
    }

    public function edit(Publication $publication): View
    {
        //$this->authorize('update', $publication);

        $tags = Tag::orderBy('name')->get();
        $publication->load('tags');

        return view('admin.publications.edit', compact('publication', 'tags'));
    }

    public function update(UpdatePublicationRequest $request, Publication $publication): RedirectResponse
    {
        $data = $request->validated();

        // Map form field names to database field names
        if (isset($data['journal_name'])) {
            $data['journal'] = $data['journal_name'];
            unset($data['journal_name']);
        }

        if ($request->hasFile('publication_file')) {
            $this->deleteFile($publication->publication_file_path);
            $data['publication_file_path'] = $this->storeFile($request->file('publication_file'));
        }

        $publication->update($data);

        if ($request->has('tags')) {
            $publication->tags()->sync($request->tags ?: []);
        }

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publication updated successfully.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        //$this->authorize('delete', $publication);

        $this->deleteFile($publication->publication_file_path);

        $publication->tags()->detach();
        $publication->delete();

        return redirect()->route('admin.publications.index')
            ->with('success', 'Publication deleted successfully.');
    }

    /**
     * Store a publication PDF under public/documents/publications so it is
     * directly downloadable, and return its relative path.
     */
    private function storeFile(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('documents/publications'), $filename);

        return 'documents/publications/' . $filename;
    }

    /**
     * Delete a publication PDF, whichever location it was uploaded to.
     */
    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (file_exists(public_path($path))) {
            @unlink(public_path($path));
        }

        Storage::disk('local')->delete($path);
    }
}
