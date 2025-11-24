@extends('layouts.admin-modern')

@section('title', $jobOffer->title)
@section('page-title', 'Job Offer Details')

@section('content')
<!-- Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6 lg:mb-8">
    <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $jobOffer->title }}</h1>
            @if($jobOffer->featured)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-star mr-1"></i>Featured
                </span>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ ucfirst($jobOffer->project_type) }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}
            </span>
            @if($jobOffer->status == 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>Active
                </span>
            @elseif($jobOffer->status == 'filled')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-check mr-1"></i>Filled
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times mr-1"></i>Cancelled
                </span>
            @endif
        </div>
    </div>
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('jobs.show', $jobOffer) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-external-link-alt mr-2"></i>View Public Page
        </a>
        <a href="{{ route('admin.job-offers.edit', $jobOffer) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('admin.job-offers.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-blue-600"></i>
                    Description
                </h2>
            </div>
            <div class="p-4 lg:p-6">
                <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $jobOffer->description }}</p>
            </div>
        </div>

        <!-- Requirements -->
        @if($jobOffer->requirements)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-purple-600"></i>
                        Requirements
                    </h2>
                </div>
                <div class="p-4 lg:p-6">
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $jobOffer->requirements }}</p>
                </div>
            </div>
        @endif

        <!-- Required Skills -->
        @if($jobOffer->skills->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-4 lg:p-6 border-b border-gray-100">
                    <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-star mr-2 text-yellow-600"></i>
                        Required Skills ({{ $jobOffer->skills->count() }})
                    </h2>
                </div>
                <div class="p-4 lg:p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($jobOffer->skills as $skill)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors">
                                @if($skill->simple_icon)
                                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/{{ $skill->simple_icon }}.svg"
                                         alt="{{ $skill->name }}" class="w-4 h-4 mr-2">
                                @elseif($skill->logo)
                                    <img src="{{ asset('storage/' . $skill->logo) }}"
                                         alt="{{ $skill->name }}" class="w-4 h-4 mr-2">
                                @elseif($skill->icon)
                                    <i class="{{ $skill->icon }} mr-2"></i>
                                @endif
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Applications -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center justify-between">
                    <span class="flex items-center">
                        <i class="fas fa-paper-plane mr-2 text-green-600"></i>
                        Applications ({{ $stats['total'] }})
                    </span>
                    @if($stats['pending'] > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ $stats['pending'] }} pending
                        </span>
                    @endif
                </h2>
            </div>

            @if($jobOffer->applications->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($jobOffer->applications as $application)
                        <div class="p-4 lg:p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $application->full_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $application->email }}</p>
                                    @if($application->phone)
                                        <p class="text-sm text-gray-600">{{ $application->phone }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @if($application->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($application->status == 'reviewed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Reviewed
                                        </span>
                                    @elseif($application->status == 'shortlisted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Shortlisted
                                        </span>
                                    @elseif($application->status == 'accepted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Accepted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500">{{ $application->applied_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @if($application->cover_letter)
                                <p class="text-sm text-gray-700 mb-3 line-clamp-2">{{ $application->cover_letter }}</p>
                            @endif
                            <div class="flex items-center gap-2">
                                @if($application->cv_path)
                                    <a href="{{ $application->cv_url }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-file-pdf mr-2"></i>View CV
                                    </a>
                                    <a href="{{ $application->cv_url }}" download
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                @endif
                                @if($application->portfolio_url)
                                    <a href="{{ $application->portfolio_url }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                                        <i class="fas fa-external-link-alt mr-2"></i>Portfolio
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
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
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Application Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Application Stats</h3>
            </div>
            <div class="p-4 lg:p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total</span>
                    <span class="text-lg font-bold text-gray-900">{{ $stats['total'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pending</span>
                    <span class="text-sm font-semibold text-yellow-600">{{ $stats['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Reviewed</span>
                    <span class="text-sm font-semibold text-blue-600">{{ $stats['reviewed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Shortlisted</span>
                    <span class="text-sm font-semibold text-purple-600">{{ $stats['shortlisted'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Accepted</span>
                    <span class="text-sm font-semibold text-green-600">{{ $stats['accepted'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Rejected</span>
                    <span class="text-sm font-semibold text-red-600">{{ $stats['rejected'] }}</span>
                </div>
            </div>
        </div>

        <!-- Job Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Job Details</h3>
            </div>
            <div class="p-4 lg:p-6 space-y-4">
                @if($jobOffer->budget_min || $jobOffer->budget_max)
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Budget</span>
                        <span class="text-lg font-bold text-purple-600">{{ $jobOffer->budget_range }}</span>
                    </div>
                @endif

                @if($jobOffer->duration)
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Duration</span>
                        <span class="text-sm font-medium text-gray-900">{{ $jobOffer->duration }}</span>
                    </div>
                @endif

                @if($jobOffer->location)
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Location</span>
                        <span class="text-sm font-medium text-gray-900">{{ $jobOffer->location }}</span>
                    </div>
                @endif

                <div>
                    <span class="text-sm text-gray-500 block mb-1">Posted By</span>
                    <span class="text-sm font-medium text-gray-900">{{ $jobOffer->user->name }}</span>
                </div>

                <div>
                    <span class="text-sm text-gray-500 block mb-1">Published</span>
                    <span class="text-sm font-medium text-gray-900">{{ $jobOffer->published_at->format('M d, Y g:i A') }}</span>
                </div>

                <div>
                    <span class="text-sm text-gray-500 block mb-1">Last Updated</span>
                    <span class="text-sm font-medium text-gray-900">{{ $jobOffer->updated_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 lg:p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-4 lg:p-6 space-y-2">
                <form method="POST" action="{{ route('admin.job-offers.toggle-featured', $jobOffer) }}">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium {{ $jobOffer->featured ? 'text-gray-700 bg-gray-100 hover:bg-gray-200' : 'text-yellow-700 bg-yellow-50 hover:bg-yellow-100' }} border border-gray-300 rounded-lg transition-colors">
                        <i class="fas fa-star mr-2"></i>
                        {{ $jobOffer->featured ? 'Unfeature' : 'Feature' }} Job
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.job-offers.update-status', $jobOffer) }}" class="space-y-2">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" {{ $jobOffer->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="filled" {{ $jobOffer->status === 'filled' ? 'selected' : '' }}>Filled</option>
                        <option value="cancelled" {{ $jobOffer->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync mr-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200">
            <div class="p-4 lg:p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    Danger Zone
                </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Permanently delete this job offer and all {{ $stats['total'] }} application(s). This action cannot be undone.
                </p>
                <form method="POST" action="{{ route('admin.job-offers.destroy', $jobOffer) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors" onclick="return confirm('Are you sure you want to delete this job offer? All {{ $stats['total'] }} application(s) will also be deleted.')">
                        <i class="fas fa-trash mr-2"></i>Delete Job Offer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
