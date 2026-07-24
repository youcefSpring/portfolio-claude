@extends('layouts.admin-modern')

@section('title', 'Education')
@section('page-title', 'Education')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 lg:mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Education</h1>
        <p class="text-gray-600 mt-1">Shown in the “Background” section of your homepage</p>
    </div>
    <button type="button" data-edu-add
            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Add Education
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
    @forelse($education as $entry)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $entry->degree }}</h3>
                        @if($entry->is_current)
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">Current</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-blue-600">{{ $entry->field_of_study }}</p>
                    <p class="text-sm text-gray-600">{{ $entry->institution }}@if($entry->location) · {{ $entry->location }}@endif</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $entry->start_date?->format('M Y') }}
                        @if($entry->is_current)
                            — Present
                        @elseif($entry->end_date)
                            — {{ $entry->end_date->format('M Y') }}
                        @endif
                        @if($entry->grade) · Grade: {{ $entry->grade }} @endif
                    </p>
                    @if($entry->description)
                        <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ Str::limit($entry->description, 200) }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button"
                            data-edu-edit
                            data-degree="{{ $entry->degree }}"
                            data-field="{{ $entry->field_of_study }}"
                            data-institution="{{ $entry->institution }}"
                            data-location="{{ $entry->location }}"
                            data-grade="{{ $entry->grade }}"
                            data-description="{{ $entry->description }}"
                            data-start="{{ $entry->start_date?->format('Y-m-d') }}"
                            data-end="{{ $entry->end_date?->format('Y-m-d') }}"
                            data-current="{{ $entry->is_current ? 1 : 0 }}"
                            data-action="{{ route('admin.education.update', $entry) }}"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>

                    <form method="POST" action="{{ route('admin.education.destroy', $entry) }}"
                          onsubmit="return confirm('Delete “{{ $entry->degree }}”?')">
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
                <i class="fas fa-graduation-cap text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No education entries yet</h3>
            <p class="text-gray-500 mb-6">Add your degrees so they appear on the homepage.</p>
            <button type="button" data-edu-add class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add First Entry
            </button>
        </div>
    @endforelse
</div>

<!-- Add / Edit modal -->
<div id="edu-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/60" data-edu-close></div>

    <div class="relative min-h-full flex items-start sm:items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl my-8">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="edu-modal-title" class="text-lg font-semibold text-gray-900">Add Education</h2>
                <button type="button" data-edu-close class="p-2 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="edu-form" method="POST" action="{{ route('admin.education.store') }}">
                @csrf
                <input type="hidden" name="_method" id="edu-method" value="POST">

                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label for="edu-degree" class="block text-sm font-medium text-gray-700 mb-2">Degree <span class="text-red-500">*</span></label>
                        <input type="text" id="edu-degree" name="degree" required placeholder="MSc Computer Science"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="edu-field" class="block text-sm font-medium text-gray-700 mb-2">Field of Study <span class="text-red-500">*</span></label>
                        <input type="text" id="edu-field" name="field_of_study" required placeholder="Software Engineering"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="edu-institution" class="block text-sm font-medium text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                        <input type="text" id="edu-institution" name="institution" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="edu-location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" id="edu-location" name="location"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="edu-start" class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" id="edu-start" name="start_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label for="edu-end" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="edu-end" name="end_date"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors disabled:bg-gray-100 disabled:text-gray-400">
                    </div>

                    <div>
                        <label for="edu-grade" class="block text-sm font-medium text-gray-700 mb-2">Grade</label>
                        <input type="text" id="edu-grade" name="grade" placeholder="Very good"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <label class="flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer self-end">
                        <input type="checkbox" id="edu-current" name="is_current" value="1"
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-900">Currently studying here</span>
                    </label>

                    <div class="md:col-span-2">
                        <label for="edu-description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea id="edu-description" name="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" data-edu-close class="px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i><span id="edu-submit-label">Create Entry</span>
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
    const storeAction = @json(route('admin.education.store'));

    const modal = document.getElementById('edu-modal');
    const form = document.getElementById('edu-form');
    const method = document.getElementById('edu-method');
    const title = document.getElementById('edu-modal-title');
    const submitLabel = document.getElementById('edu-submit-label');
    const current = document.getElementById('edu-current');
    const end = document.getElementById('edu-end');

    const fields = {
        degree: document.getElementById('edu-degree'),
        field: document.getElementById('edu-field'),
        institution: document.getElementById('edu-institution'),
        location: document.getElementById('edu-location'),
        grade: document.getElementById('edu-grade'),
        description: document.getElementById('edu-description'),
        start: document.getElementById('edu-start'),
    };

    function syncEndDate() {
        end.disabled = current.checked;
        if (current.checked) end.value = '';
    }

    current.addEventListener('change', syncEndDate);

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        fields.degree.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    document.querySelectorAll('[data-edu-add]').forEach(btn => btn.addEventListener('click', function () {
        form.reset();
        form.action = storeAction;
        method.value = 'POST';
        title.textContent = 'Add Education';
        submitLabel.textContent = 'Create Entry';
        syncEndDate();
        openModal();
    }));

    document.querySelectorAll('[data-edu-edit]').forEach(btn => btn.addEventListener('click', function () {
        const d = this.dataset;
        form.reset();
        form.action = d.action;
        method.value = 'PUT';
        title.textContent = 'Edit Education';
        submitLabel.textContent = 'Save Changes';

        fields.degree.value = d.degree || '';
        fields.field.value = d.field || '';
        fields.institution.value = d.institution || '';
        fields.location.value = d.location || '';
        fields.grade.value = d.grade || '';
        fields.description.value = d.description || '';
        fields.start.value = d.start || '';
        end.value = d.end || '';
        current.checked = d.current === '1';

        syncEndDate();
        openModal();
    }));

    document.querySelectorAll('[data-edu-close]').forEach(el => el.addEventListener('click', closeModal));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // A disabled input is not submitted; re-enable right before submit so a
    // cleared end date still reaches the server.
    form.addEventListener('submit', () => end.disabled = false);

    @if($errors->any())
        document.querySelector('[data-edu-add]').click();
        fields.degree.value = @json(old('degree'));
        fields.field.value = @json(old('field_of_study'));
        fields.institution.value = @json(old('institution'));
        fields.location.value = @json(old('location'));
        fields.grade.value = @json(old('grade'));
        fields.description.value = @json(old('description'));
        fields.start.value = @json(old('start_date'));
        end.value = @json(old('end_date'));
        current.checked = {{ old('is_current') ? 'true' : 'false' }};
        syncEndDate();
    @endif
});
</script>
@endpush
