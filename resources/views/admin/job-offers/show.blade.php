@extends('layouts.admin-modern')

@section('title', $jobOffer->title)
@section('page-title', 'Job Offer Details')

@php
    $offerStatuses = [
        'active' => ['Active', 'fa-circle-play', 'bg-green-100 text-green-800', 'Visible and accepting applications'],
        'closed' => ['Closed', 'fa-lock', 'bg-orange-100 text-orange-800', 'Visible, applications disabled'],
        'filled' => ['Filled', 'fa-check', 'bg-gray-100 text-gray-800', 'Hidden from the public site'],
        'cancelled' => ['Cancelled', 'fa-times', 'bg-red-100 text-red-800', 'Hidden from the public site'],
    ];
    $appStatuses = [
        'pending' => ['Pending', 'bg-yellow-100 text-yellow-800', 'text-yellow-600'],
        'reviewed' => ['Reviewed', 'bg-blue-100 text-blue-800', 'text-blue-600'],
        'shortlisted' => ['Shortlisted', 'bg-purple-100 text-purple-800', 'text-purple-600'],
        'accepted' => ['Accepted', 'bg-green-100 text-green-800', 'text-green-600'],
        'rejected' => ['Rejected', 'bg-red-100 text-red-800', 'text-red-600'],
    ];
    $currentStatus = $offerStatuses[$jobOffer->status] ?? $offerStatuses['cancelled'];
    $imageCount = $jobOffer->images ? count($jobOffer->images) : 0;
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">
    <div class="flex-1 min-w-0">
        <a href="{{ route('admin.job-offers.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-2">
            <i class="fas fa-arrow-left mr-2"></i>All job offers
        </a>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 break-words">{{ $jobOffer->title }}</h1>
        <div class="flex flex-wrap items-center gap-2 mt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $currentStatus[2] }}">
                <i class="fas {{ $currentStatus[1] }} mr-1"></i>{{ $currentStatus[0] }}
            </span>
            @if($jobOffer->featured)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-star mr-1"></i>Featured
                </span>
            @endif
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ ucfirst($jobOffer->project_type) }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}
            </span>
            <span class="text-xs text-gray-500">Published {{ $jobOffer->published_at->diffForHumans() }}</span>
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        @if($jobOffer->isActive() || $jobOffer->isClosed())
            <a href="{{ route('jobs.show', $jobOffer) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 bg-white text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i>Public Page
            </a>
        @endif
        <a href="{{ route('admin.job-offers.edit', $jobOffer) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
</div>

<!-- Stats strip (click to filter applications) -->
<div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-6">
    <button type="button" data-filter-shortcut="all"
            class="text-left bg-white rounded-xl border border-gray-100 shadow-sm p-3 hover:border-blue-300 transition-colors">
        <span class="block text-xs text-gray-500">Total</span>
        <span class="block text-xl font-bold text-gray-900">{{ $stats['total'] }}</span>
    </button>
    @foreach($appStatuses as $key => [$label, $badge, $text])
        <button type="button" data-filter-shortcut="{{ $key }}"
                class="text-left bg-white rounded-xl border border-gray-100 shadow-sm p-3 hover:border-blue-300 transition-colors">
            <span class="block text-xs text-gray-500">{{ $label }}</span>
            <span class="block text-xl font-bold {{ $text }}">{{ $stats[$key] }}</span>
        </button>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main: tabs -->
    <div class="lg:col-span-2 min-w-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="border-b border-gray-100 px-2 overflow-x-auto">
                <nav class="flex gap-1" role="tablist">
                    <button type="button" role="tab" data-tab="applications"
                            class="tab-btn inline-flex items-center px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-paper-plane mr-2"></i>Applications
                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700">{{ $stats['total'] }}</span>
                    </button>
                    <button type="button" role="tab" data-tab="overview"
                            class="tab-btn inline-flex items-center px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                        <i class="fas fa-file-alt mr-2"></i>Overview
                    </button>
                    @if($imageCount > 0)
                        <button type="button" role="tab" data-tab="media"
                                class="tab-btn inline-flex items-center px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap">
                            <i class="fas fa-images mr-2"></i>Media
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700">{{ $imageCount }}</span>
                        </button>
                    @endif
                </nav>
            </div>

            <!-- Applications tab -->
            <div data-panel="applications" role="tabpanel" class="tab-panel hidden">
                @if($jobOffer->applications->count() > 0)
                    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" data-filter="all" class="filter-chip px-3 py-1 rounded-full text-xs font-medium border border-gray-200">All</button>
                            @foreach($appStatuses as $key => [$label])
                                @if($stats[$key] > 0)
                                    <button type="button" data-filter="{{ $key }}" class="filter-chip px-3 py-1 rounded-full text-xs font-medium border border-gray-200">
                                        {{ $label }} <span class="text-gray-400">{{ $stats[$key] }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <div class="relative flex-1 sm:w-56">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="search" id="applicant-search" placeholder="Search name or email"
                                       class="w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @if($jobOffer->applications->whereNotNull('cv_file_path')->count() > 0)
                                <a href="{{ route('admin.job-offers.download-cvs', $jobOffer) }}" title="Download all CVs"
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                                    <i class="fas fa-file-archive sm:mr-2"></i><span class="hidden sm:inline">All CVs</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <ul class="divide-y divide-gray-100" id="applicant-list">
                        @foreach($jobOffer->applications as $application)
                            @php $as = $appStatuses[$application->status] ?? $appStatuses['pending']; @endphp
                            <li>
                                <button type="button" data-open-applicant="{{ $application->id }}"
                                        data-status="{{ $application->status }}"
                                        data-search="{{ Str::lower($application->full_name.' '.$application->email) }}"
                                        class="applicant-row w-full text-left px-4 lg:px-6 py-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
                                    <span class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-sm">
                                        {{ Str::upper(Str::substr($application->full_name, 0, 1)) }}
                                    </span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block font-semibold text-gray-900 truncate">{{ $application->full_name }}</span>
                                        <span class="block text-sm text-gray-500 truncate">{{ $application->email }}</span>
                                    </span>
                                    <span class="hidden sm:flex items-center gap-2 text-gray-400 text-sm">
                                        @if($application->cv_path)<i class="fas fa-file-pdf" title="CV attached"></i>@endif
                                        @if($application->cover_letter)<i class="fas fa-envelope-open-text" title="Cover letter"></i>@endif
                                        @if($application->notes)<i class="fas fa-sticky-note" title="Has notes"></i>@endif
                                    </span>
                                    <span class="flex flex-col items-end gap-1 flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $as[1] }}">{{ $as[0] }}</span>
                                        <span class="text-xs text-gray-500">{{ $application->applied_at->diffForHumans() }}</span>
                                    </span>
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div id="applicant-empty" class="hidden p-8 text-center text-sm text-gray-500">No applications match this filter.</div>
                @else
                    <div class="p-8 lg:p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                            <i class="fas fa-inbox text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Applications Yet</h3>
                        <p class="text-gray-600">When candidates apply to this position, their applications will appear here.</p>
                    </div>
                @endif
            </div>

            <!-- Overview tab -->
            <div data-panel="overview" role="tabpanel" class="tab-panel hidden p-4 lg:p-6 space-y-8">
                <section>
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $jobOffer->description }}</p>
                </section>

                @if($jobOffer->requirements)
                    <section>
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Requirements</h2>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $jobOffer->requirements }}</p>
                    </section>
                @endif

                @if($jobOffer->skills->count() > 0)
                    <section>
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Required Skills ({{ $jobOffer->skills->count() }})</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($jobOffer->skills as $skill)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-800">
                                    @if($skill->simple_icon)
                                        <img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/{{ $skill->simple_icon }}.svg"
                                             alt="{{ $skill->name }}" class="w-4 h-4 mr-2">
                                    @elseif($skill->logo)
                                        <img src="{{ $skill->logo_url }}"
                                             alt="{{ $skill->name }}" class="w-4 h-4 mr-2">
                                    @elseif($skill->icon)
                                        <i class="{{ $skill->icon }} mr-2"></i>
                                    @endif
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <!-- Media tab -->
            @if($imageCount > 0)
                <div data-panel="media" role="tabpanel" class="tab-panel hidden p-4 lg:p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($jobOffer->images as $index => $image)
                            <button type="button" data-lightbox="{{ asset('storage/' . $image) }}" class="relative group block">
                                <img src="{{ asset('storage/' . $image) }}"
                                     alt="{{ $jobOffer->title }} - Image {{ $index + 1 }}"
                                     class="w-full h-40 object-cover rounded-lg border border-gray-200 group-hover:opacity-90 transition-opacity">
                                <span class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-xs rounded">{{ $index + 1 }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Side panel -->
    <aside class="space-y-6 lg:sticky lg:top-6 self-start">
        <!-- Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Offer Status</h3>
            <form method="POST" action="{{ route('admin.job-offers.update-status', $jobOffer) }}" class="space-y-2">
                @csrf
                @method('PUT')
                @foreach($offerStatuses as $key => [$label, $icon, $badge, $hint])
                    <button type="submit" name="status" value="{{ $key }}"
                            class="w-full flex items-center gap-3 px-3 py-2 rounded-lg border text-left transition-colors {{ $jobOffer->status === $key ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-gray-50' }}"
                            {{ $jobOffer->status === $key ? 'disabled' : '' }}>
                        <span class="w-8 h-8 rounded-full flex items-center justify-center {{ $badge }}"><i class="fas {{ $icon }} text-xs"></i></span>
                        <span class="flex-1">
                            <span class="block text-sm font-medium text-gray-900">{{ $label }}</span>
                            <span class="block text-xs text-gray-500">{{ $hint }}</span>
                        </span>
                        @if($jobOffer->status === $key)
                            <i class="fas fa-check-circle text-blue-600"></i>
                        @endif
                    </button>
                @endforeach
            </form>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500 mb-3">Details</h3>
            <dl class="space-y-3 text-sm">
                @if($jobOffer->budget_min || $jobOffer->budget_max)
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Budget</dt><dd class="font-semibold text-purple-600 text-right">{{ $jobOffer->budget_range }}</dd></div>
                @endif
                @if($jobOffer->duration)
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Duration</dt><dd class="font-medium text-gray-900 text-right">{{ $jobOffer->duration }}</dd></div>
                @endif
                @if($jobOffer->location)
                    <div class="flex justify-between gap-4"><dt class="text-gray-500">Location</dt><dd class="font-medium text-gray-900 text-right">{{ $jobOffer->location }}</dd></div>
                @endif
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Posted by</dt><dd class="font-medium text-gray-900 text-right">{{ $jobOffer->user->name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Published</dt><dd class="font-medium text-gray-900 text-right">{{ $jobOffer->published_at->format('M d, Y g:i A') }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Updated</dt><dd class="font-medium text-gray-900 text-right">{{ $jobOffer->updated_at->format('M d, Y g:i A') }}</dd></div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-5 space-y-2">
            <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $jobOffer) }}">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium {{ $jobOffer->featured ? 'text-gray-700 bg-gray-100 hover:bg-gray-200' : 'text-yellow-700 bg-yellow-50 hover:bg-yellow-100' }} border border-gray-300 rounded-lg transition-colors">
                    <i class="fas fa-star mr-2"></i>{{ $jobOffer->featured ? 'Unfeature' : 'Feature' }} Job
                </button>
            </form>
            <form method="POST" action="{{ route('admin.job-offers.destroy', $jobOffer) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors" onclick="return confirm('Are you sure you want to delete this job offer? All {{ $stats['total'] }} application(s) will also be deleted.')">
                    <i class="fas fa-trash mr-2"></i>Delete Job Offer
                </button>
            </form>
        </div>
    </aside>
</div>

<!-- Applicant slide-over panel -->
<div id="applicant-panel" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div data-close-panel class="absolute inset-0 bg-gray-900/40 transition-opacity"></div>
    <div class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl flex flex-col translate-x-full transition-transform duration-200" id="applicant-panel-body">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <button type="button" data-step="-1" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50" title="Previous (←)"><i class="fas fa-chevron-up text-xs"></i></button>
                <button type="button" data-step="1" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50" title="Next (→)"><i class="fas fa-chevron-down text-xs"></i></button>
                <span id="applicant-position" class="text-xs text-gray-500 ml-1"></span>
            </div>
            <button type="button" data-close-panel class="w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100" title="Close (Esc)"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto">
            @foreach($jobOffer->applications as $application)
                @php $as = $appStatuses[$application->status] ?? $appStatuses['pending']; @endphp
                <div data-applicant="{{ $application->id }}" class="hidden p-5 space-y-6">
                    <div class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
                            {{ Str::upper(Str::substr($application->full_name, 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <h2 class="text-xl font-bold text-gray-900 break-words">{{ $application->full_name }}</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $as[1] }}">{{ $as[0] }}</span>
                                <span class="text-xs text-gray-500">Applied {{ $application->applied_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <section>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Contact</h3>
                        <div class="space-y-1.5 text-sm">
                            <a href="mailto:{{ $application->email }}" class="flex items-center text-gray-700 hover:text-blue-600"><i class="fas fa-envelope w-5 text-gray-400"></i>{{ $application->email }}</a>
                            @if($application->phone)
                                <a href="tel:{{ $application->phone }}" class="flex items-center text-gray-700 hover:text-blue-600"><i class="fas fa-phone w-5 text-gray-400"></i>{{ $application->phone }}</a>
                            @endif
                        </div>
                        @if($application->github_url || $application->linkedin_url || $application->telegram || $application->portfolio_url)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @if($application->github_url)
                                    <a href="{{ $application->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"><i class="fab fa-github mr-1.5"></i>GitHub</a>
                                @endif
                                @if($application->linkedin_url)
                                    <a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"><i class="fab fa-linkedin mr-1.5"></i>LinkedIn</a>
                                @endif
                                @if($application->telegram)
                                    <a href="{{ $application->telegram_url }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"><i class="fab fa-telegram mr-1.5"></i>{{ '@' . $application->telegram }}</a>
                                @endif
                                @if($application->portfolio_url)
                                    <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50"><i class="fas fa-globe mr-1.5"></i>Portfolio</a>
                                @endif
                            </div>
                        @endif
                    </section>

                    @if($application->cv_path)
                        <section>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">CV / Resume</h3>
                            <div class="flex gap-2">
                                <a href="{{ $application->cv_url }}" target="_blank"
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-file-pdf mr-2"></i>View CV
                                </a>
                                <a href="{{ $application->cv_url }}" download
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            </div>
                        </section>
                    @endif

                    @if($application->cover_letter)
                        <section>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Cover Letter</h3>
                            <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed bg-gray-50 rounded-lg p-4">{{ $application->cover_letter }}</p>
                        </section>
                    @endif

                    <section>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Review</h3>
                        <form method="POST" action="{{ route('admin.job-applications.update-status', $application) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($appStatuses as $key => [$label, $badge])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="status" value="{{ $key }}" class="peer sr-only" {{ $application->status === $key ? 'checked' : '' }}>
                                        <span class="block text-center px-3 py-2 text-xs font-medium rounded-lg border border-gray-200 text-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 hover:bg-gray-50">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <textarea name="notes" rows="3" placeholder="Private notes about this applicant..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $application->notes }}</textarea>
                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Save Review
                            </button>
                        </form>
                    </section>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Image lightbox -->
<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4" data-close-lightbox>
    <button type="button" class="absolute top-4 right-4 text-white text-2xl" data-close-lightbox><i class="fas fa-times"></i></button>
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded object-contain">
</div>
@endsection

@push('scripts')
<script>
(function () {
    // Tabs (remembered via URL hash)
    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');
    function showTab(name) {
        if (!document.querySelector(`[data-panel="${name}"]`)) name = 'applications';
        tabs.forEach(t => {
            const active = t.dataset.tab === name;
            t.classList.toggle('border-blue-600', active);
            t.classList.toggle('text-blue-600', active);
            t.classList.toggle('border-transparent', !active);
            t.classList.toggle('text-gray-500', !active);
            t.setAttribute('aria-selected', active);
        });
        panels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== name));
        history.replaceState(null, '', '#' + name);
    }
    tabs.forEach(t => t.addEventListener('click', () => showTab(t.dataset.tab)));
    showTab(location.hash.slice(1) || 'applications');

    // Applicant filtering
    const rows = [...document.querySelectorAll('.applicant-row')];
    const chips = document.querySelectorAll('.filter-chip');
    const search = document.getElementById('applicant-search');
    const empty = document.getElementById('applicant-empty');
    let currentFilter = 'all';
    function applyFilter() {
        const q = (search?.value || '').trim().toLowerCase();
        let shown = 0;
        rows.forEach(r => {
            const match = (currentFilter === 'all' || r.dataset.status === currentFilter) && r.dataset.search.includes(q);
            r.parentElement.classList.toggle('hidden', !match);
            if (match) shown++;
        });
        empty?.classList.toggle('hidden', shown > 0);
        chips.forEach(c => {
            const active = c.dataset.filter === currentFilter;
            c.classList.toggle('bg-blue-600', active);
            c.classList.toggle('text-white', active);
            c.classList.toggle('border-blue-600', active);
        });
    }
    function setFilter(f) { currentFilter = f; applyFilter(); }
    chips.forEach(c => c.addEventListener('click', () => setFilter(c.dataset.filter)));
    search?.addEventListener('input', applyFilter);
    document.querySelectorAll('[data-filter-shortcut]').forEach(b => b.addEventListener('click', () => {
        showTab('applications');
        setFilter(b.dataset.filterShortcut);
    }));
    applyFilter();

    // Applicant slide-over
    const panel = document.getElementById('applicant-panel');
    const body = document.getElementById('applicant-panel-body');
    const position = document.getElementById('applicant-position');
    let currentId = null;
    function visibleIds() {
        return rows.filter(r => !r.parentElement.classList.contains('hidden')).map(r => r.dataset.openApplicant);
    }
    function openApplicant(id) {
        currentId = id;
        panel.querySelectorAll('[data-applicant]').forEach(el => el.classList.toggle('hidden', el.dataset.applicant !== id));
        const ids = visibleIds();
        position.textContent = ids.includes(id) ? `${ids.indexOf(id) + 1} of ${ids.length}` : '';
        panel.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => body.classList.remove('translate-x-full'));
    }
    function closePanel() {
        body.classList.add('translate-x-full');
        setTimeout(() => panel.classList.add('hidden'), 200);
        document.body.style.overflow = '';
        currentId = null;
    }
    function step(dir) {
        const ids = visibleIds();
        const i = ids.indexOf(currentId);
        if (i === -1 || !ids.length) return;
        openApplicant(ids[(i + dir + ids.length) % ids.length]);
    }
    rows.forEach(r => r.addEventListener('click', () => openApplicant(r.dataset.openApplicant)));
    panel.querySelectorAll('[data-close-panel]').forEach(b => b.addEventListener('click', closePanel));
    panel.querySelectorAll('[data-step]').forEach(b => b.addEventListener('click', () => step(+b.dataset.step)));

    // Reopen the same applicant after saving a review (the redirect drops the hash)
    panel.querySelectorAll('form').forEach(f => f.addEventListener('submit', () => {
        try { sessionStorage.setItem('jobOfferApplicant', currentId); } catch (e) {}
    }));
    try {
        const saved = sessionStorage.getItem('jobOfferApplicant');
        if (saved && panel.querySelector(`[data-applicant="${saved}"]`)) {
            sessionStorage.removeItem('jobOfferApplicant');
            showTab('applications');
            openApplicant(saved);
        }
    } catch (e) {}

    // Lightbox
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    document.querySelectorAll('[data-lightbox]').forEach(b => b.addEventListener('click', () => {
        lightboxImg.src = b.dataset.lightbox;
        lightbox.classList.remove('hidden');
    }));
    lightbox.addEventListener('click', e => {
        if (e.target.closest('[data-close-lightbox]') && e.target !== lightboxImg) lightbox.classList.add('hidden');
    });

    // Keyboard
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (!lightbox.classList.contains('hidden')) lightbox.classList.add('hidden');
            else if (currentId) closePanel();
        }
        if (currentId && ['ArrowDown', 'ArrowRight'].includes(e.key) && !e.target.matches('textarea, input')) { e.preventDefault(); step(1); }
        if (currentId && ['ArrowUp', 'ArrowLeft'].includes(e.key) && !e.target.matches('textarea, input')) { e.preventDefault(); step(-1); }
    });
})();
</script>
@endpush
