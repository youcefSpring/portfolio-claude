@extends('layouts.admin-modern')

@section('title', 'Job Applications')
@section('page-title', 'Applications')

@php
    $statusTabs = ['' => 'All'] + \App\Models\JobApplication::STATUSES;
    $currentStatus = request('status', '');
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Applications</h1>
        <p class="text-gray-600 mt-1">Review candidates across all job offers</p>
    </div>
    <a href="{{ route('admin.job-offers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
        <i class="fas fa-briefcase mr-2"></i>Job Offers
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <!-- Status tabs -->
    <div class="border-b border-gray-100 px-2 overflow-x-auto">
        <nav class="flex gap-1">
            @foreach($statusTabs as $key => $label)
                @php $active = $currentStatus === $key; @endphp
                <a href="{{ route('admin.job-applications.index', array_filter(['status' => $key, 'job_offer_id' => request('job_offer_id'), 'search' => request('search')])) }}"
                   class="inline-flex items-center px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $active ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $active ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">{{ $stats[$key ?: 'total'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Toolbar -->
    <form method="GET" action="{{ route('admin.job-applications.index') }}" class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
        @if($currentStatus)
            <input type="hidden" name="status" value="{{ $currentStatus }}">
        @endif
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email or phone"
                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select name="job_offer_id" onchange="this.form.submit()"
                class="sm:w-64 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All job offers</option>
            @foreach($jobOffers as $offer)
                <option value="{{ $offer->id }}" {{ request('job_offer_id') == $offer->id ? 'selected' : '' }}>{{ $offer->title }}</option>
            @endforeach
        </select>
        @if(request()->hasAny(['search', 'job_offer_id']))
            <a href="{{ route('admin.job-applications.index', array_filter(['status' => $currentStatus])) }}"
               class="inline-flex items-center justify-center px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                <i class="fas fa-times mr-1"></i>Clear
            </a>
        @endif
    </form>

    @if($applications->count() > 0)
        <!-- Bulk actions bar -->
        <div id="bulk-bar" class="hidden px-4 py-3 bg-blue-50 border-b border-blue-100 flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-blue-800"><span id="bulk-count">0</span> selected</span>
            <form method="POST" action="{{ route('admin.job-applications.bulk-status') }}" class="bulk-form flex items-center gap-2">
                @csrf
                <select name="status" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm">
                    @foreach(\App\Models\JobApplication::STATUSES as $key => $label)
                        <option value="{{ $key }}">Mark as {{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Apply</button>
            </form>
            <form method="POST" action="{{ route('admin.job-applications.bulk-delete') }}" class="bulk-form"
                  onsubmit="return confirm('Delete the selected applications? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50">
                    <i class="fas fa-trash mr-1"></i>Delete
                </button>
            </form>
        </div>

        <div class="px-4 lg:px-6 py-2 border-b border-gray-100 flex items-center gap-4 text-xs text-gray-500">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" id="select-all" class="rounded border-gray-300">
                Select all on this page
            </label>
        </div>

        <ul class="divide-y divide-gray-100">
            @foreach($applications as $application)
                <li data-open-item="{{ $application->id }}"
                    class="px-4 lg:px-6 py-4 hover:bg-gray-50 transition-colors flex items-center gap-4 cursor-pointer">
                    <input type="checkbox" value="{{ $application->id }}" class="row-check rounded border-gray-300" aria-label="Select {{ $application->full_name }}">
                    <span class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm">
                        {{ Str::upper(Str::substr($application->full_name, 0, 1)) }}
                    </span>
                    <span class="flex-1 min-w-0">
                        <span class="block font-semibold text-gray-900 truncate">{{ $application->full_name }}</span>
                        <span class="block text-sm text-gray-500 truncate">{{ $application->email }}</span>
                    </span>
                    <span class="hidden md:block w-56 min-w-0 text-sm text-gray-600 truncate" title="{{ $application->jobOffer?->title }}">
                        <i class="fas fa-briefcase mr-1 text-gray-400"></i>{{ $application->jobOffer?->title ?? '—' }}
                    </span>
                    <span class="hidden sm:flex items-center gap-2 text-gray-400 text-sm">
                        @if($application->cv_path)<i class="fas fa-file-pdf" title="CV attached"></i>@endif
                        @if($application->cover_letter)<i class="fas fa-envelope-open-text" title="Cover letter"></i>@endif
                        @if($application->notes)<i class="fas fa-sticky-note" title="Has notes"></i>@endif
                    </span>
                    <span class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $application->status_color }}-100 text-{{ $application->status_color }}-800">{{ $application->status_label }}</span>
                        <span class="text-xs text-gray-500">{{ $application->applied_at->diffForHumans() }}</span>
                    </span>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                </li>
            @endforeach
        </ul>

        @if($applications->hasPages())
            <div class="p-4 lg:p-6 border-t border-gray-100">
                {{ $applications->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="p-8 lg:p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <i class="fas fa-inbox text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
            <p class="text-gray-600">
                {{ request()->hasAny(['search', 'job_offer_id', 'status']) ? 'No applications match the current filters.' : 'Applications will appear here when candidates apply to your job offers.' }}
            </p>
        </div>
    @endif
</div>

<x-admin.slide-over>
    @foreach($applications as $application)
        <div data-item="{{ $application->id }}" class="hidden p-5 space-y-6">
            @include('admin.job-applications._details', ['application' => $application, 'showJob' => true, 'showDelete' => true])
        </div>
    @endforeach
</x-admin.slide-over>
@endsection

@push('scripts')
<script>
(function () {
    const checks = [...document.querySelectorAll('.row-check')];
    const selectAll = document.getElementById('select-all');
    const bar = document.getElementById('bulk-bar');
    if (!bar) return;

    function selectedIds() { return checks.filter(c => c.checked).map(c => c.value); }
    function refresh() {
        const ids = selectedIds();
        bar.classList.toggle('hidden', ids.length === 0);
        document.getElementById('bulk-count').textContent = ids.length;
        selectAll.checked = ids.length === checks.length;
        selectAll.indeterminate = ids.length > 0 && ids.length < checks.length;
    }
    checks.forEach(c => c.addEventListener('change', refresh));
    selectAll.addEventListener('change', () => { checks.forEach(c => c.checked = selectAll.checked); refresh(); });

    // Copy the selected ids into each bulk form as it submits
    document.querySelectorAll('.bulk-form').forEach(form => form.addEventListener('submit', () => {
        form.querySelectorAll('input[name="application_ids[]"]').forEach(i => i.remove());
        selectedIds().forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'application_ids[]';
            input.value = id;
            form.appendChild(input);
        });
    }));
})();
</script>
@endpush
