@extends('layouts.admin-modern')

@section('title', 'Job Offers Management')
@section('page-title', 'Job Offers')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 lg:mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Job Offers</h1>
            <p class="text-gray-600 mt-1">Manage consulting, freelance, contract and internship opportunities</p>
        </div>
        <a href="{{ route('admin.job-offers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <i class="fas fa-plus mr-2"></i>
            Add New Job Offer
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 lg:p-6 mb-6 lg:mb-8">
        <form method="GET" action="{{ route('admin.job-offers.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text"
                       id="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search titles..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Project Type</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="type" name="type">
                    <option value="">All Types</option>
                    <option value="consulting" {{ request('type') == 'consulting' ? 'selected' : '' }}>Consulting</option>
                    <option value="freelance" {{ request('type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                    <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ request('type') == 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.job-offers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Job Offers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 lg:p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-briefcase mr-2 text-blue-600"></i>
                    Job Offers ({{ $jobOffers->total() }})
                </h2>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <span class="text-sm text-gray-500">Filtered results</span>
                @endif
            </div>
        </div>

        @if($jobOffers->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm text-gray-600 border-b border-gray-100">
                            <th class="pb-3 px-6 font-medium">Title</th>
                            <th class="pb-3 px-3 font-medium">Type</th>
                            <th class="pb-3 px-3 font-medium">Location</th>
                            <th class="pb-3 px-3 font-medium">Status</th>
                            <th class="pb-3 px-3 font-medium">Applications</th>
                            <th class="pb-3 px-3 font-medium">Posted</th>
                            <th class="pb-3 px-6 font-medium w-48">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($jobOffers as $job)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        @if($job->images && count($job->images) > 0)
                                            <img src="{{ asset('storage/' . $job->images[0]) }}"
                                                 alt="{{ $job->title }}"
                                                 class="w-12 h-12 rounded-lg object-cover mr-3 flex-shrink-0"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg items-center justify-center mr-3 hidden flex-shrink-0">
                                                <i class="fas fa-briefcase text-gray-400"></i>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                                <i class="fas fa-briefcase text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900 flex items-center">
                                                {{ $job->title }}
                                                @if($job->featured)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-star mr-1"></i>Featured
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-gray-500 text-xs mt-1">{{ Str::limit($job->description, 60) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($job->project_type) }}
                                    </span>
                                </td>
                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst(str_replace('-', ' ', $job->location_type)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-3">
                                    @if($job->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle mr-1" style="font-size: 6px;"></i>Active
                                        </span>
                                    @elseif($job->status == 'filled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-check mr-1"></i>Filled
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Cancelled
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-3">
                                    <span class="text-gray-900 font-medium">{{ $job->applications->count() }}</span>
                                    @if($job->applications->where('status', 'pending')->count() > 0)
                                        <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $job->applications->where('status', 'pending')->count() }} new
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-3 text-gray-600">
                                    {{ $job->published_at->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.job-offers.show', $job) }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.job-offers.edit', $job) }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.job-offers.toggle-featured', $job) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-600 hover:text-yellow-600 hover:bg-yellow-50 rounded transition-colors" title="{{ $job->featured ? 'Unfeature' : 'Feature' }}">
                                                <i class="fas fa-star{{ $job->featured ? '' : '-o' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.job-offers.destroy', $job) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this job offer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-100">
                @foreach($jobOffers as $job)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 flex items-center">
                                    {{ $job->title }}
                                    @if($job->featured)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>Featured
                                        </span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($job->description, 60) }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($job->project_type) }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst(str_replace('-', ' ', $job->location_type)) }}
                            </span>
                            @if($job->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($job->status == 'filled')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Filled
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-600 mb-3">
                            <span>{{ $job->applications->count() }} applications</span>
                            <span>{{ $job->published_at->format('M d, Y') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.job-offers.show', $job) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                View
                            </a>
                            <a href="{{ route('admin.job-offers.edit', $job) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="p-4 lg:p-6 border-t border-gray-100">
                {{ $jobOffers->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="p-8 lg:p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-briefcase text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No job offers found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['search', 'type', 'status']))
                        Try adjusting your filters or <a href="{{ route('admin.job-offers.index') }}" class="text-blue-600 hover:underline">clear all filters</a>.
                    @else
                        Get started by creating your first job offer.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('admin.job-offers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Job Offer
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection
