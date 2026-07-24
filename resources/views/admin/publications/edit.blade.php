@extends('layouts.admin-modern')

@section('title', 'Edit Publication')
@section('page-title', 'Edit Publication')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 lg:mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Publication</h1>
            <p class="text-gray-600 mt-1">Update publication information and details</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('admin.publications.show', $publication) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium">
                <i class="fas fa-eye mr-2"></i>View Publication
            </a>
            <a href="{{ route('admin.publications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Publications
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                        Publication Information
                    </h2>
                </div>
                <div class="p-4 lg:p-6">
                    <form method="POST" action="{{ route('admin.publications.update', $publication) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Publication Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $publication->title) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Authors -->
                            <div class="md:col-span-2">
                                <label for="authors" class="block text-sm font-medium text-gray-700 mb-2">Authors <span class="text-red-500">*</span></label>
                                <textarea id="authors" name="authors" rows="2" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('authors') border-red-500 @enderror">{{ old('authors', $publication->authors) }}</textarea>
                                <p class="text-gray-500 text-xs mt-1">Citation format, e.g. “Smith, J., Doe, A., &amp; Johnson, M.”</p>
                                @error('authors')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Publication Type <span class="text-red-500">*</span></label>
                                <select id="type" name="type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type') border-red-500 @enderror">
                                    <option value="">Select Type</option>
                                    @foreach([
                                        'journal' => 'Journal Article',
                                        'conference' => 'Conference Paper',
                                        'book' => 'Book',
                                        'book_chapter' => 'Book Chapter',
                                        'thesis' => 'Thesis',
                                        'report' => 'Report',
                                        'preprint' => 'Preprint',
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', $publication->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Publication Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('status') border-red-500 @enderror">
                                    @foreach([
                                        'published' => 'Published',
                                        'accepted' => 'Accepted',
                                        'under_review' => 'Under Review',
                                        'in_preparation' => 'In Preparation',
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ old('status', $publication->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Journal / Venue -->
                            <div>
                                <label for="journal_name" class="block text-sm font-medium text-gray-700 mb-2">Journal / Conference Name</label>
                                <input type="text" id="journal_name" name="journal_name" value="{{ old('journal_name', $publication->journal) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('journal_name') border-red-500 @enderror">
                                @error('journal_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue / Publisher</label>
                                <input type="text" id="venue" name="venue" value="{{ old('venue', $publication->venue) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('venue') border-red-500 @enderror">
                                @error('venue')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Volume / Issue / Pages / Year -->
                            <div class="md:col-span-2 grid grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="volume" class="block text-sm font-medium text-gray-700 mb-2">Volume</label>
                                    <input type="text" id="volume" name="volume" value="{{ old('volume', $publication->volume) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('volume') border-red-500 @enderror">
                                    @error('volume')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="issue" class="block text-sm font-medium text-gray-700 mb-2">Issue</label>
                                    <input type="text" id="issue" name="issue" value="{{ old('issue', $publication->issue) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('issue') border-red-500 @enderror">
                                    @error('issue')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pages" class="block text-sm font-medium text-gray-700 mb-2">Pages</label>
                                    <input type="text" id="pages" name="pages" value="{{ old('pages', $publication->pages) }}" placeholder="123-145"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pages') border-red-500 @enderror">
                                    @error('pages')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                                    <input type="number" id="year" name="year" min="1900" max="{{ date('Y') + 1 }}" required
                                           value="{{ old('year', $publication->year) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('year') border-red-500 @enderror">
                                    @error('year')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- DOI / URL -->
                            <div>
                                <label for="doi" class="block text-sm font-medium text-gray-700 mb-2">DOI</label>
                                <input type="text" id="doi" name="doi" value="{{ old('doi', $publication->doi) }}" placeholder="10.1109/tas.2024.8.2.112"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('doi') border-red-500 @enderror">
                                <p class="text-gray-500 text-xs mt-1">Used as the public link when no URL is set.</p>
                                @error('doi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">Publication URL</label>
                                <input type="url" id="url" name="url" value="{{ old('url', $publication->url) }}" placeholder="https://..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('url') border-red-500 @enderror">
                                <p class="text-gray-500 text-xs mt-1">Shown as “Read More” on the homepage.</p>
                                @error('url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PDF -->
                            <div class="md:col-span-2">
                                <label for="publication_file" class="block text-sm font-medium text-gray-700 mb-2">PDF File</label>

                                @if($publication->publication_file_path)
                                    <div class="flex flex-wrap items-center gap-3 mb-3 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                        <span class="text-sm text-gray-700">{{ basename($publication->publication_file_path) }}</span>
                                        <a href="{{ $publication->file_url }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </div>
                                @endif

                                <input type="file" id="publication_file" name="publication_file" accept=".pdf"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('publication_file') border-red-500 @enderror">
                                <p class="text-gray-500 text-xs mt-1">PDF only — max 20MB. Uploading replaces the current file.</p>
                                @error('publication_file')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Abstract -->
                            <div class="md:col-span-2">
                                <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">Abstract</label>
                                <textarea id="abstract" name="abstract" rows="6"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('abstract') border-red-500 @enderror">{{ old('abstract', $publication->abstract) }}</textarea>
                                @error('abstract')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keywords -->
                            <div class="md:col-span-2">
                                <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords</label>
                                <textarea id="keywords" name="keywords" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('keywords') border-red-500 @enderror">{{ old('keywords', $publication->keywords) }}</textarea>
                                <p class="text-gray-500 text-xs mt-1">Comma-separated research topics</p>
                                @error('keywords')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tags -->
                            @if(isset($tags) && $tags->count() > 0)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-48 overflow-y-auto p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                        @foreach($tags as $tag)
                                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                       {{ in_array($tag->id, old('tags', $publication->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <span>{{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1">Categorise this publication</p>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="md:col-span-2 flex flex-col sm:flex-row sm:justify-between gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('admin.publications.show', $publication) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <i class="fas fa-save mr-2"></i>Update Publication
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <!-- Citation Preview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-quote-left mr-2 text-blue-600"></i>
                        Citation Preview
                    </h2>
                </div>
                <div class="p-4 lg:p-6">
                    <div id="citation-preview" class="text-sm text-gray-600 italic">
                        Citation will appear here as you update the form
                    </div>
                </div>
            </div>

            <!-- Publication Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Publication Info
                    </h2>
                </div>
                <div class="p-4 lg:p-6 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-medium text-gray-900">{{ $publication->created_at->format('M j, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium text-gray-900">{{ $publication->updated_at->format('M j, Y') }}</span>
                    </div>

                    @if($publication->link_url)
                        <div class="pt-3">
                            <a href="{{ $publication->link_url }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors font-medium">
                                <i class="fas fa-external-link-alt mr-2"></i>Open Public Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-sm border border-red-200">
                <div class="p-4 lg:p-6 border-b border-red-200">
                    <h2 class="text-lg font-semibold text-red-600 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Danger Zone
                    </h2>
                </div>
                <div class="p-4 lg:p-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Permanently delete this publication. This action cannot be undone.
                    </p>
                    <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium" onclick="return confirm('Are you sure you want to delete this publication?')">
                            <i class="fas fa-trash mr-2"></i>Delete Publication
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // No auto-generation needed since we only use year field now

        // Citation preview update
        function updateCitationPreview() {
            const title = document.getElementById('title').value;
            const authors = document.getElementById('authors').value;
            const year = document.getElementById('year').value;
            const journal = document.getElementById('journal_name').value;
            const volume = document.getElementById('volume').value;
            const issue = document.getElementById('issue').value;
            const pages = document.getElementById('pages').value;
            const doi = document.getElementById('doi').value;

            let citation = '';

            if (authors) citation += authors;
            if (year) citation += ` (${year}).`;
            if (title) citation += ` ${title}.`;
            if (journal) citation += ` <em>${journal}</em>`;
            if (volume) citation += `, ${volume}`;
            if (issue) citation += `(${issue})`;
            if (pages) citation += `, ${pages}`;
            if (doi) citation += `. https://doi.org/${doi}`;

            document.getElementById('citation-preview').innerHTML = citation || '<em>Citation will appear here as you update the form</em>';
        }

        // Add event listeners for real-time preview
        ['title', 'authors', 'year', 'journal_name', 'volume', 'issue', 'pages', 'doi'].forEach(function(id) {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updateCitationPreview);
            }
        });

        // Form submission handling
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>Updating...';
            submitButton.disabled = true;

            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 10000);
        });

        // File size validation
        const pdfFileInput = document.getElementById('publication_file');
        pdfFileInput.addEventListener('change', function() {
            if (this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // Size in MB
                if (fileSize > 20) {
                    this.setCustomValidity('File size must be less than 20MB');
                    this.classList.add('border-red-500');
                    this.classList.remove('border-gray-300');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                }
            }
        });

        // Initialize citation preview
        updateCitationPreview();
    });
</script>
@endpush