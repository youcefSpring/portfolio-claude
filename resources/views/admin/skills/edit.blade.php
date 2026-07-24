@extends('layouts.admin-modern')

@section('title', 'Edit Skill')
@section('page-title', 'Edit Skill')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 lg:mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Skill</h1>
        <p class="text-gray-600 mt-1">{{ $skill->name }}</p>
    </div>
    <a href="{{ route('admin.skills.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>Back to Skills
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 lg:p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-bolt mr-2 text-blue-600"></i>Skill
            </h2>
        </div>

        <form method="POST" action="{{ route('admin.skills.update', $skill) }}" enctype="multipart/form-data" class="p-4 lg:p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $skill->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo picker -->
            @include('admin.skills.partials.logo-picker')

            <!-- Featured -->
            <label class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer">
                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $skill->is_featured) ? 'checked' : '' }}
                       class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span>
                    <span class="block text-sm font-medium text-gray-900">Featured skill</span>
                    <span class="block text-xs text-gray-500">Featured skills appear on the public homepage</span>
                </span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.skills.index') }}" class="px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Cancel</a>
                <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
