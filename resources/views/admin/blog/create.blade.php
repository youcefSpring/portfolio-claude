@extends('layouts.admin-modern')

@section('title', 'Create Blog Post')
@section('page-title', 'Create Blog Post')

@section('content')
<!-- Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6 lg:mb-8">
    <div class="flex-1">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Create New Blog Post</h1>
        <p class="text-gray-600">Write and publish a new blog post</p>
    </div>
    <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back to Posts
    </a>
</div>

<form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6">
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 @enderror"
                                   id="slug" name="slug" value="{{ old('slug') }}">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('excerpt') border-red-500 @enderror"
                                      id="excerpt" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Brief summary of the post (optional)</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="content" class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-2">
                                    <button type="button" id="togglePreview" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-eye mr-1"></i>Preview
                                    </button>
                                    <span class="text-gray-400">|</span>
                                    <span class="text-xs text-gray-500">Markdown supported</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!-- Editor -->
                                <div id="editorSection">
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors font-mono text-sm @error('content') border-red-500 @enderror"
                                              id="content" name="content" rows="20" required placeholder="# Your Blog Post Title

Write your content here using **Markdown** syntax.

## Heading 2
### Heading 3

- List item 1
- List item 2

**Bold text** and *italic text*

```javascript
// Code blocks are supported
console.log('Hello, world!');
```

> Blockquotes look great too!

[Links](https://example.com) and images work as well.">{{ old('content') }}</textarea>

                                    @error('content')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <!-- Markdown Help -->
                                    <div class="mt-2 p-3 bg-blue-50 rounded-lg">
                                        <p class="text-xs text-blue-800 font-medium mb-2">Markdown Quick Reference:</p>
                                        <div class="grid grid-cols-2 gap-2 text-xs text-blue-700">
                                            <div>
                                                <code># Heading 1</code><br>
                                                <code>## Heading 2</code><br>
                                                <code>**Bold**</code><br>
                                                <code>*Italic*</code>
                                            </div>
                                            <div>
                                                <code>- List item</code><br>
                                                <code>[Link](url)</code><br>
                                                <code>`code`</code><br>
                                                <code>```code block```</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div id="previewSection" class="hidden lg:block">
                                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50 min-h-[500px]">
                                        <div class="text-sm text-gray-500 mb-3 pb-3 border-b border-gray-200">
                                            <i class="fas fa-eye mr-1"></i>Live Preview
                                        </div>
                                        <div id="markdownPreview" class="markdown-content">
                                            <p class="text-gray-400 italic">Start typing to see preview...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Publish Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Publish Settings</h3>
                </div>
                <div class="p-4 lg:p-6 space-y-4">
                    <div>
                        <label for="is_published" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('is_published') border-red-500 @enderror"
                                id="is_published" name="is_published">
                            <option value="0" {{ old('is_published', '0') == '0' ? 'selected' : '' }}>Draft</option>
                            <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('is_published')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('published_at') border-red-500 @enderror"
                               id="published_at" name="published_at" value="{{ old('published_at') }}">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to publish immediately</p>
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('is_featured') border-red-500 @enderror"
                               id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                            Featured Post
                        </label>
                        @error('is_featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>
                </div>
                <div class="p-4 lg:p-6">
                    <div>
                        <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('featured_image') border-red-500 @enderror"
                               id="featured_image" name="featured_image" accept="image/*">
                        <p class="mt-1 text-sm text-gray-500">Recommended: 1200x600px</p>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Tags</h3>
                </div>
                <div class="p-4 lg:p-6">
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <div class="flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       id="tag_{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                <label class="ml-2 text-sm text-gray-700" for="tag_{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-check mr-2"></i>Create Post
                </button>
                <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>

@section('styles')
<style>
    /* Markdown Content Styling for Preview */
    .markdown-content {
        line-height: 1.7;
        color: #374151;
        font-size: 0.95rem;
    }

    .markdown-content h1, .markdown-content h2, .markdown-content h3,
    .markdown-content h4, .markdown-content h5, .markdown-content h6 {
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
    }

    .markdown-content h1 { font-size: 1.5rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.25rem; }
    .markdown-content h2 { font-size: 1.25rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.125rem; }
    .markdown-content h3 { font-size: 1.125rem; }
    .markdown-content h4 { font-size: 1rem; }

    .markdown-content p { margin-bottom: 1rem; }
    .markdown-content a { color: #2563eb; text-decoration: underline; }
    .markdown-content ul, .markdown-content ol { margin-bottom: 1rem; padding-left: 1.5rem; }
    .markdown-content li { margin-bottom: 0.25rem; }

    .markdown-content blockquote {
        border-left: 3px solid #2563eb;
        background: #eff6ff;
        margin: 1rem 0;
        padding: 0.75rem 1rem;
        border-radius: 0 4px 4px 0;
        font-style: italic;
    }

    .markdown-content pre {
        background: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 4px;
        overflow-x: auto;
        margin: 1rem 0;
        font-size: 0.875rem;
    }

    .markdown-content code {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.875rem;
    }

    .markdown-content p code, .markdown-content li code {
        background: #f1f5f9;
        color: #db2777;
        padding: 0.125rem 0.25rem;
        border-radius: 2px;
    }

    .markdown-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
        font-size: 0.875rem;
    }

    .markdown-content th, .markdown-content td {
        padding: 0.5rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .markdown-content th {
        background: #f8fafc;
        font-weight: 600;
    }

    .markdown-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 1rem 0;
    }

    .markdown-content hr {
        border: none;
        height: 1px;
        background: #e5e7eb;
        margin: 2rem 0;
    }

    .markdown-content strong { font-weight: 700; }
    .markdown-content em { font-style: italic; }
</style>
@endsection

@section('scripts')
<!-- Include marked.js for client-side Markdown parsing -->
<script src="https://cdn.jsdelivr.net/npm/marked@4.3.0/marked.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
    });

    // Markdown preview functionality
    const contentTextarea = document.getElementById('content');
    const previewDiv = document.getElementById('markdownPreview');
    const togglePreviewBtn = document.getElementById('togglePreview');
    const previewSection = document.getElementById('previewSection');
    let previewVisible = window.innerWidth >= 1024; // Show by default on large screens

    // Configure marked options
    marked.setOptions({
        breaks: true,
        gfm: true,
        headerIds: true,
        sanitize: false,
        smartLists: true,
        smartypants: true,
        tables: true
    });

    function updatePreview() {
        const markdownText = contentTextarea.value;
        if (markdownText.trim()) {
            try {
                const html = marked.parse(markdownText);
                previewDiv.innerHTML = html;
            } catch (error) {
                previewDiv.innerHTML = '<p class="text-red-500">Error parsing Markdown: ' + error.message + '</p>';
            }
        } else {
            previewDiv.innerHTML = '<p class="text-gray-400 italic">Start typing to see preview...</p>';
        }
    }

    function togglePreview() {
        previewVisible = !previewVisible;

        if (previewVisible) {
            previewSection.classList.remove('hidden');
            togglePreviewBtn.innerHTML = '<i class="fas fa-eye-slash mr-1"></i>Hide Preview';
            updatePreview();
        } else {
            previewSection.classList.add('hidden');
            togglePreviewBtn.innerHTML = '<i class="fas fa-eye mr-1"></i>Show Preview';
        }
    }

    // Event listeners
    contentTextarea.addEventListener('input', function() {
        if (previewVisible) {
            updatePreview();
        }
    });

    togglePreviewBtn.addEventListener('click', togglePreview);

    // Initialize preview on large screens
    if (previewVisible) {
        updatePreview();
    }

    // Handle responsive behavior
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024 && !previewVisible) {
            // Show preview on large screens
            previewVisible = true;
            previewSection.classList.remove('hidden');
            togglePreviewBtn.innerHTML = '<i class="fas fa-eye-slash mr-1"></i>Hide Preview';
            updatePreview();
        } else if (window.innerWidth < 1024 && previewVisible) {
            // Hide preview on small screens
            previewVisible = false;
            previewSection.classList.add('hidden');
            togglePreviewBtn.innerHTML = '<i class="fas fa-eye mr-1"></i>Show Preview';
        }
    });

    // Add helpful keyboard shortcuts
    contentTextarea.addEventListener('keydown', function(e) {
        // Tab key for indentation
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + '  ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 2;
        }

        // Ctrl/Cmd + B for bold
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const selectedText = this.value.substring(start, end);
            const replacement = '**' + selectedText + '**';
            this.value = this.value.substring(0, start) + replacement + this.value.substring(end);
            this.selectionStart = start + 2;
            this.selectionEnd = start + 2 + selectedText.length;
            updatePreview();
        }

        // Ctrl/Cmd + I for italic
        if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const selectedText = this.value.substring(start, end);
            const replacement = '*' + selectedText + '*';
            this.value = this.value.substring(0, start) + replacement + this.value.substring(end);
            this.selectionStart = start + 1;
            this.selectionEnd = start + 1 + selectedText.length;
            updatePreview();
        }
    });
});
</script>
@endsection
@endsection