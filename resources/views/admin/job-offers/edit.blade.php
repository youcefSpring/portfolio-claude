@extends('layouts.admin-modern')

@section('title', 'Edit Job Offer')
@section('page-title', 'Edit Job Offer')

@section('content')
<!-- Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6 lg:mb-8">
    <div class="flex-1">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Edit Job Offer</h1>
        <p class="text-gray-600">Update job offer information and requirements</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('admin.job-offers.show', $jobOffer) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-eye mr-2"></i>View Job Offer
        </a>
        <a href="{{ route('admin.job-offers.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Job Offers
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.job-offers.update', $jobOffer) }}" enctype="multipart/form-data" id="update-form">
    @csrf
    @method('PUT')
    <input type="hidden" name="form_type" value="update">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6">
                    <div class="space-y-6">
                        <!-- Job Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title', $jobOffer->title) }}" required placeholder="e.g., Full-Stack Developer Consultant">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                                      id="description" name="description" rows="6" required placeholder="Describe the opportunity, responsibilities, and what you're looking for...">{{ old('description', $jobOffer->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Requirements -->
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('requirements') border-red-500 @enderror"
                                      id="requirements" name="requirements" rows="4" placeholder="List the qualifications, skills, and experience required...">{{ old('requirements', $jobOffer->requirements) }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Budget Range</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="budget_min" class="block text-xs text-gray-600 mb-1">Minimum ($)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('budget_min') border-red-500 @enderror"
                                           id="budget_min" name="budget_min" value="{{ old('budget_min', $jobOffer->budget_min) }}" min="0" step="0.01" placeholder="1000">
                                    @error('budget_min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="budget_max" class="block text-xs text-gray-600 mb-1">Maximum ($)</label>
                                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('budget_max') border-red-500 @enderror"
                                           id="budget_max" name="budget_max" value="{{ old('budget_max', $jobOffer->budget_max) }}" min="0" step="0.01" placeholder="5000">
                                    @error('budget_max')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Leave empty if you prefer not to disclose budget</p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duration') border-red-500 @enderror"
                                   id="duration" name="duration" value="{{ old('duration', $jobOffer->duration) }}" placeholder="e.g., 3-6 months, Ongoing, 40 hours/week">
                            @error('duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location Details</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('location') border-red-500 @enderror"
                                   id="location" name="location" value="{{ old('location', $jobOffer->location) }}" placeholder="e.g., San Francisco, CA or Worldwide">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Required Skills -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Required Skills</label>

                            <!-- Selected Skills Tags -->
                            <div id="selectedSkillsContainer" class="mb-2 min-h-[40px] border border-gray-300 rounded-lg p-2 flex flex-wrap gap-2 @error('skill_ids') border-red-500 @enderror">
                                <div id="selectedSkillsTags" class="flex flex-wrap gap-2"></div>
                            </div>

                            <!-- Skills Search Input -->
                            <div class="relative">
                                <input type="text"
                                       id="skillsSearchInput"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Type to search skills and press Enter to add..."
                                       autocomplete="off">

                                <!-- Autocomplete Dropdown -->
                                <div id="skillsDropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                                    <!-- Skills suggestions will appear here -->
                                </div>
                            </div>

                            <p class="mt-2 text-sm text-gray-500">Type skill name and press Enter or click to add. Click X to remove.</p>
                            @error('skill_ids')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Hidden inputs container for selected skills -->
                            <div id="skillsHiddenInputs"></div>
                        </div>

                        <!-- Images -->
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Images</label>
                            @if($jobOffer->images && count($jobOffer->images) > 0)
                                <div class="mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Current Images:</p>
                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                                        @foreach($jobOffer->images as $image)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $image) }}"
                                                     alt="Job offer image"
                                                     class="rounded-lg border border-gray-200 w-full h-24 object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Current images will be kept unless replaced.</p>
                                </div>
                            @endif
                            <div id="image-preview-container" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mb-3"></div>
                            <input type="file"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   id="images"
                                   name="images[]"
                                   accept="image/jpeg,image/jpg,image/png,image/gif,image/svg+xml,image/webp"
                                   multiple onchange="previewImages(event)">
                            <p class="mt-1 text-sm text-gray-500">Upload screenshots or mockups (JPG, PNG, GIF, SVG, WebP - max 5MB each, up to 10 images).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Job Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Job Details</h3>
                </div>
                <div class="p-4 lg:p-6 space-y-4">
                    <div>
                        <label for="project_type" class="block text-sm font-medium text-gray-700 mb-2">Project Type <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('project_type') border-red-500 @enderror"
                                id="project_type" name="project_type" required>
                            <option value="">Select Type</option>
                            <option value="consulting" {{ old('project_type', $jobOffer->project_type) === 'consulting' ? 'selected' : '' }}>Consulting</option>
                            <option value="freelance" {{ old('project_type', $jobOffer->project_type) === 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="contract" {{ old('project_type', $jobOffer->project_type) === 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('project_type', $jobOffer->project_type) === 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                        @error('project_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location_type" class="block text-sm font-medium text-gray-700 mb-2">Location Type <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('location_type') border-red-500 @enderror"
                                id="location_type" name="location_type" required>
                            <option value="">Select Location Type</option>
                            <option value="remote" {{ old('location_type', $jobOffer->location_type) === 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="on-site" {{ old('location_type', $jobOffer->location_type) === 'on-site' ? 'selected' : '' }}>On-Site</option>
                            <option value="hybrid" {{ old('location_type', $jobOffer->location_type) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                        @error('location_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('status') border-red-500 @enderror"
                                id="status" name="status" required>
                            <option value="active" {{ old('status', $jobOffer->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="filled" {{ old('status', $jobOffer->status) === 'filled' ? 'selected' : '' }}>Filled</option>
                            <option value="cancelled" {{ old('status', $jobOffer->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('published_at') border-red-500 @enderror"
                               id="published_at" name="published_at" value="{{ old('published_at', $jobOffer->published_at?->format('Y-m-d\TH:i')) }}">
                        <p class="mt-1 text-sm text-gray-500">When should this job be visible?</p>
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('featured') border-red-500 @enderror"
                               id="featured" name="featured" value="1" {{ old('featured', $jobOffer->featured) ? 'checked' : '' }}>
                        <label for="featured" class="ml-2 block text-sm text-gray-900">
                            Featured Job Offer
                        </label>
                        @error('featured')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Job Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        Job Info
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Created:</span>
                            <div class="text-sm text-gray-900">{{ $jobOffer->created_at->format('F d, Y \a\t g:i A') }}</div>
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">Last Updated:</span>
                            <div class="text-sm text-gray-900">{{ $jobOffer->updated_at->format('F d, Y \a\t g:i A') }}</div>
                        </div>

                        <div>
                            <span class="text-sm text-gray-500">Applications:</span>
                            <div class="text-sm text-gray-900">{{ $jobOffer->applications->count() }} total</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-check mr-2"></i>Update Job Offer
                </button>
                <a href="{{ route('admin.job-offers.show', $jobOffer) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Cancel
                </a>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-sm border border-red-200">
                <div class="p-4 lg:p-6">
                    <h3 class="text-lg font-semibold text-red-600 mb-4 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Danger Zone
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Permanently delete this job offer and all related applications. This action cannot be undone.
                    </p>
                    <button type="button" 
                            class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors" 
                            onclick="if(confirm('Are you sure you want to delete this job offer? All applications will also be deleted.')) document.getElementById('delete-job-offer-form').submit();">
                        <i class="fas fa-trash mr-2"></i>Delete Job Offer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="delete-job-offer-form" method="POST" action="{{ route('admin.job-offers.destroy', $jobOffer) }}" class="hidden">
    @csrf
    @method('DELETE')
</form>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Skills data from server
    const allSkills = @json($skills->map(function($skill) {
        return ['id' => $skill->id, 'name' => $skill->name];
    }));

    const selectedSkills = new Map(); // Store selected skills (id => name)
    const searchInput = document.getElementById('skillsSearchInput');
    const dropdown = document.getElementById('skillsDropdown');
    const tagsContainer = document.getElementById('selectedSkillsTags');
    const hiddenInputsContainer = document.getElementById('skillsHiddenInputs');

    // Initialize with existing values
    const existingSkills = @json($jobOffer->skills->map(function($skill) {
        return ['id' => $skill->id, 'name' => $skill->name];
    }));
    
    if (existingSkills.length > 0) {
        existingSkills.forEach(skill => {
            addSkill(skill.id, skill.name);
        });
    }

    // Initialize with old values if validation failed
    const oldSkillIds = @json(old('skill_ids', []));
    if (oldSkillIds.length > 0) {
        // Clear existing if there are old values (to avoid duplicates from old input)
        tagsContainer.innerHTML = '';
        hiddenInputsContainer.innerHTML = '';
        selectedSkills.clear();
        
        oldSkillIds.forEach(skillId => {
            const skill = allSkills.find(s => s.id == skillId);
            if (skill) {
                addSkill(skill.id, skill.name);
            }
        });
    }

    // Search and filter skills
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();

        if (searchTerm === '') {
            dropdown.classList.add('hidden');
            return;
        }

        const filtered = allSkills.filter(skill =>
            !selectedSkills.has(skill.id.toString()) &&
            skill.name.toLowerCase().includes(searchTerm)
        );

        if (filtered.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        dropdown.innerHTML = filtered.map(skill => `
            <div class="px-4 py-2 hover:bg-blue-50 cursor-pointer skill-option" data-id="${skill.id}" data-name="${skill.name}">
                <span class="text-sm text-gray-900">${skill.name}</span>
            </div>
        `).join('');

        dropdown.classList.remove('hidden');

        // Add click handlers to options
        document.querySelectorAll('.skill-option').forEach(option => {
            option.addEventListener('click', function() {
                addSkill(this.dataset.id, this.dataset.name);
                searchInput.value = '';
                dropdown.classList.add('hidden');
            });
        });
    });

    // Handle Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const firstOption = dropdown.querySelector('.skill-option');
            if (firstOption) {
                addSkill(firstOption.dataset.id, firstOption.dataset.name);
                searchInput.value = '';
                dropdown.classList.add('hidden');
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Add skill tag
    function addSkill(id, name) {
        id = id.toString();
        if (selectedSkills.has(id)) return;

        selectedSkills.set(id, name);

        // Create tag element
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium';
        tag.innerHTML = `
            ${name}
            <button type="button" class="ml-2 text-blue-600 hover:text-blue-800 focus:outline-none" onclick="removeSkill('${id}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        tag.dataset.skillId = id;
        tagsContainer.appendChild(tag);

        // Create hidden input
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'skill_ids[]';
        hiddenInput.value = id;
        hiddenInput.dataset.skillId = id;
        hiddenInputsContainer.appendChild(hiddenInput);
    }

    // Remove skill tag (make it global so onclick can access it)
    window.removeSkill = function(id) {
        id = id.toString();
        selectedSkills.delete(id);

        // Remove tag
        const tag = tagsContainer.querySelector(`[data-skill-id="${id}"]`);
        if (tag) tag.remove();

        // Remove hidden input
        const hiddenInput = hiddenInputsContainer.querySelector(`[data-skill-id="${id}"]`);
        if (hiddenInput) hiddenInput.remove();
    };

    // Form submission handling
    const updateForm = document.getElementById('update-form');
    updateForm.addEventListener('submit', function(e) {
        const submitButton = updateForm.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;

        submitButton.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Updating...';
        submitButton.disabled = true;

        // Re-enable after 10 seconds as fallback
        setTimeout(function() {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }, 10000);
    });
});
</script>
@endsection
@endsection
