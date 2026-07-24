@extends('layouts.admin-modern')

@section('title', 'Experience')
@section('page-title', 'Experience')

@php
    $employmentTypes = ['full-time', 'part-time', 'contract', 'freelance', 'internship'];
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 lg:mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Experience</h1>
        <p class="text-gray-600 mt-1">Drives the homepage timeline and the “Years Experience” stat</p>
    </div>
    <button type="button" data-exp-add
            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Add Experience
    </button>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
    </div>
@endif

<!-- List -->
<div class="space-y-4">
    @forelse($experiences as $experience)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $experience->position }}</h3>
                        @if($experience->is_current)
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">Current</span>
                        @endif
                        @if($experience->employment_type)
                            <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ ucfirst(str_replace('-', ' ', $experience->employment_type)) }}</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-blue-600">{{ $experience->company }}@if($experience->location) · {{ $experience->location }}@endif</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $experience->start_date?->format('M Y') }}
                        @if($experience->is_current)
                            — Present
                        @elseif($experience->end_date)
                            — {{ $experience->end_date->format('M Y') }}
                        @endif
                    </p>
                    @if($experience->description)
                        <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ Str::limit($experience->description, 200) }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button"
                            data-exp-edit
                            data-position="{{ $experience->position }}"
                            data-company="{{ $experience->company }}"
                            data-location="{{ $experience->location }}"
                            data-type="{{ $experience->employment_type }}"
                            data-description="{{ $experience->description }}"
                            data-start="{{ $experience->start_date?->format('Y-m-d') }}"
                            data-end="{{ $experience->end_date?->format('Y-m-d') }}"
                            data-current="{{ $experience->is_current ? 1 : 0 }}"
                            data-action="{{ route('admin.experiences.update', $experience) }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>

                    <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}"
                          onsubmit="return confirm('Delete “{{ $experience->position }}”?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-briefcase text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No experience entries yet</h3>
            <p class="text-gray-500 mb-6">Add roles so they appear on the homepage timeline.</p>
            <button type="button" data-exp-add class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add First Entry
            </button>
        </div>
    @endforelse
</div>

<!-- Add / Edit modal -->
<div id="exp-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/60" data-exp-close></div>

    <div class="relative min-h-full flex items-start sm:items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl my-8">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="exp-modal-title" class="text-lg font-semibold text-gray-900">Add Experience</h2>
                <button type="button" data-exp-close class="p-2 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="exp-form" method="POST" action="{{ route('admin.experiences.store') }}">
                @csrf
                <input type="hidden" name="_method" id="exp-method" value="POST">

                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label for="exp-position" class="block text-sm font-medium text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                        <input type="text" id="exp-position" name="position" required placeholder="Senior Backend Developer"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="exp-company" class="block text-sm font-medium text-gray-700 mb-2">Company <span class="text-red-500">*</span></label>
                        <input type="text" id="exp-company" name="company" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="exp-location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" id="exp-location" name="location"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="exp-type" class="block text-sm font-medium text-gray-700 mb-2">Employment Type</label>
                        <select id="exp-type" name="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">—</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}">{{ ucfirst(str_replace('-', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="exp-start" class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" id="exp-start" name="start_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="exp-end" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="exp-end" name="end_date"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-400">
                    </div>

                    <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer md:col-span-2">
                        <input type="checkbox" id="exp-current" name="is_current" value="1"
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-900">I currently work here</span>
                    </label>

                    <div class="md:col-span-2">
                        <label for="exp-description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea id="exp-description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" data-exp-close class="px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i><span id="exp-submit-label">Create Entry</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const storeAction = @json(route('admin.experiences.store'));

    const modal = document.getElementById('exp-modal');
    const form = document.getElementById('exp-form');
    const method = document.getElementById('exp-method');
    const title = document.getElementById('exp-modal-title');
    const submitLabel = document.getElementById('exp-submit-label');
    const current = document.getElementById('exp-current');
    const end = document.getElementById('exp-end');

    const fields = {
        position: document.getElementById('exp-position'),
        company: document.getElementById('exp-company'),
        location: document.getElementById('exp-location'),
        type: document.getElementById('exp-type'),
        description: document.getElementById('exp-description'),
        start: document.getElementById('exp-start'),
    };

    function syncEndDate() {
        end.disabled = current.checked;
        if (current.checked) end.value = '';
    }

    current.addEventListener('change', syncEndDate);

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        fields.position.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.querySelectorAll('[data-exp-add]').forEach(btn => btn.addEventListener('click', function () {
        form.reset();
        form.action = storeAction;
        method.value = 'POST';
        title.textContent = 'Add Experience';
        submitLabel.textContent = 'Create Entry';
        syncEndDate();
        openModal();
    }));

    document.querySelectorAll('[data-exp-edit]').forEach(btn => btn.addEventListener('click', function () {
        const d = this.dataset;
        form.reset();
        form.action = d.action;
        method.value = 'PUT';
        title.textContent = 'Edit Experience';
        submitLabel.textContent = 'Save Changes';

        fields.position.value = d.position || '';
        fields.company.value = d.company || '';
        fields.location.value = d.location || '';
        fields.type.value = d.type || '';
        fields.description.value = d.description || '';
        fields.start.value = d.start || '';
        end.value = d.end || '';
        current.checked = d.current === '1';

        syncEndDate();
        openModal();
    }));

    document.querySelectorAll('[data-exp-close]').forEach(el => el.addEventListener('click', closeModal));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // A disabled input is not submitted; re-enable right before submit so a
    // cleared end date still reaches the server.
    form.addEventListener('submit', () => end.disabled = false);

    @if($errors->any())
        document.querySelector('[data-exp-add]').click();
        fields.position.value = @json(old('position'));
        fields.company.value = @json(old('company'));
        fields.location.value = @json(old('location'));
        fields.type.value = @json(old('employment_type'));
        fields.description.value = @json(old('description'));
        fields.start.value = @json(old('start_date'));
        end.value = @json(old('end_date'));
        current.checked = {{ old('is_current') ? 'true' : 'false' }};
        syncEndDate();
    @endif
});
</script>
@endpush
