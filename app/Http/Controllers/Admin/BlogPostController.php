<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    /**
     * Create a new controller instance.
     */


    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::with(['user', 'tags']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $posts = $query->latest()->paginate(10);

        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new blog post.
     */
    public function create(): View
    {
        $tags = Tag::orderBy('name')->get();
        return view('admin.blog.create', compact('tags'));
    }

    /**
     * Store a newly created blog post.
     */
    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('images/blog', 'local');
        }

        $post = BlogPost::create($data);

        // Attach tags
        if ($request->has('tag_ids') && $request->tag_ids) {
            $post->tags()->attach($request->tag_ids);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified blog post.
     */
    public function show(BlogPost $blog): View
    {
        $blog->load(['user', 'tags']);
        return view('admin.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified blog post.
     */
    public function edit(BlogPost $blog): View
    {
        //$this->authorize('update', $blog);

        $tags = Tag::orderBy('name')->get();
        $blog->load('tags');

        return view('admin.blog.edit', compact('blog', 'tags'));
    }

    /**
     * Update the specified blog post.
     */
    public function update(StoreBlogPostRequest $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('local')->delete($blog->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')
                ->store('images/blog', 'local');
        }

        $blog->update($data);

        // Sync tags
        if ($request->has('tag_ids')) {
            $blog->tags()->sync($request->tag_ids ?: []);
        }

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified blog post.
     */
    public function destroy(BlogPost $blog): RedirectResponse
    {
        //$this->authorize('delete', $blog);

        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('local')->delete($blog->featured_image);
        }

        // Detach tags
        $blog->tags()->detach();

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
