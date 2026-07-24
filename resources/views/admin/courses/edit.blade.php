@extends('layouts.admin-modern')

@section('title', 'Edit Course')
@section('page-title', 'Edit Course')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 lg:mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Course</h1>
            <p class="text-gray-600 mt-1">{{ $course->title }}</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Courses
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-book mr-2 text-blue-600"></i>
                    Course Information
                </h2>
            </div>

            <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="p-4 lg:p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="is_active" value="1">
                <input type="hidden" name="is_published" value="1">
                <input type="hidden" name="status" value="active">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <!-- Name -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug (auto) -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug <span class="text-gray-400 font-normal">(auto-generated)</span></label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $course->slug) }}" readonly
                           class="w-full px-3 py-2 bg-gray-50 text-gray-500 border border-gray-200 rounded-lg @error('slug') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1"><i class="fas fa-link mr-1"></i>URL: /courses/<span id="slug-preview">{{ $course->slug }}</span></p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link -->
                <div>
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                    <input type="url" id="link" name="link" value="{{ old('link', $course->link) }}" placeholder="https://..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('link') border-red-500 @enderror">
                    @error('link')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Main photo -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Main Photo</label>

                    @if($course->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}"
                                 class="h-40 w-full object-cover rounded-lg border border-gray-200">
                            <label class="inline-flex items-center mt-2 text-sm text-gray-700">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-200">
                                <span class="ml-2">Remove current photo</span>
                            </label>
                        </div>
                    @endif

                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('image') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-1">JPG, PNG, WEBP or SVG — max 5MB</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <img id="image-preview" src="" alt="" class="hidden mt-3 h-40 w-full object-cover rounded-lg border border-gray-200">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Cancel</a>
                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        const slugPreview = document.getElementById('slug-preview');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');

        function slugify(value) {
            return value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        }

        titleInput.addEventListener('input', function () {
            slugInput.value = slugify(titleInput.value);
            slugPreview.textContent = slugInput.value || '…';
        });

        imageInput.addEventListener('change', function () {
            if (!this.files[0]) {
                imagePreview.classList.add('hidden');
                return;
            }
            imagePreview.src = URL.createObjectURL(this.files[0]);
            imagePreview.classList.remove('hidden');
        });
    });
</script>
@endpush
