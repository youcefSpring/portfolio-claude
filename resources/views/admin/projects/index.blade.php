@extends('layouts.admin-modern')

@section('title', 'Projects Management')
@section('page-title', 'Projects')

@php
    $statusTabs = ['' => 'All', 'active' => 'Active', 'featured' => 'Featured', 'archived' => 'Archived'];
    $statusBadges = [
        'active' => 'bg-green-100 text-green-700',
        'featured' => 'bg-yellow-100 text-yellow-700',
        'archived' => 'bg-gray-100 text-gray-700',
    ];
    $currentStatus = request('status', '');
    $filters = array_filter(request()->only(['search', 'skill', 'tag']));
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Projects</h1>
        <p class="text-gray-600 mt-1">Manage your professional web development portfolio projects</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
        <i class="fas fa-plus mr-2"></i>Add New Project
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <!-- Status tabs -->
    <div class="border-b border-gray-100 px-2 overflow-x-auto">
        <nav class="flex gap-1">
            @foreach($statusTabs as $key => $label)
                @php $active = $currentStatus === $key; @endphp
                <a href="{{ route('admin.projects.index', $filters + array_filter(['status' => $key])) }}"
                   class="inline-flex items-center px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $active ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $active ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">{{ $statusCounts[$key] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Toolbar -->
    <form method="GET" action="{{ route('admin.projects.index') }}" class="p-4 border-b border-gray-100 flex flex-col md:flex-row gap-3">
        @if($currentStatus)
            <input type="hidden" name="status" value="{{ $currentStatus }}">
        @endif
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search title or description"
                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <select name="skill" onchange="this.form.submit()"
                class="md:w-48 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All skills</option>
            @foreach($skills as $skill)
                <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>{{ $skill->name }}</option>
            @endforeach
        </select>
        <select name="tag" onchange="this.form.submit()"
                class="md:w-48 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">All tags</option>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>
        @if($filters)
            <a href="{{ route('admin.projects.index', array_filter(['status' => $currentStatus])) }}"
               class="inline-flex items-center justify-center px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                <i class="fas fa-times mr-1"></i>Clear
            </a>
        @endif
    </form>

    @if($projects->count() > 0)
        <ul class="divide-y divide-gray-100">
            @foreach($projects as $project)
                <li data-open-item="{{ $project->id }}"
                    class="px-4 lg:px-6 py-4 hover:bg-gray-50 transition-colors flex items-center gap-4 cursor-pointer">
                    @if($project->images && count($project->images) > 0)
                        <img src="{{ asset($project->images[0]) }}" alt="" loading="lazy"
                             class="flex-shrink-0 w-14 h-14 rounded-lg object-cover bg-gray-100"
                             onerror="this.style.visibility='hidden'">
                    @else
                        <span class="flex-shrink-0 w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-project-diagram text-gray-400"></i>
                        </span>
                    @endif
                    <span class="flex-1 min-w-0">
                        <span class="block font-semibold text-gray-900 truncate">{{ $project->title }}</span>
                        @if($project->description)
                            <span class="block text-sm text-gray-500 truncate">{{ Str::limit($project->description, 120) }}</span>
                        @endif
                        @if($project->skills->count() > 0)
                            <span class="hidden sm:flex flex-wrap gap-1 mt-1.5">
                                @foreach($project->skills->take(4) as $skill)
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs">{{ $skill->name }}</span>
                                @endforeach
                                @if($project->skills->count() > 4)
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">+{{ $project->skills->count() - 4 }}</span>
                                @endif
                            </span>
                        @endif
                    </span>
                    <span class="hidden md:flex items-center gap-2 text-gray-400 text-sm">
                        @if($project->live_demo_url)<i class="fas fa-external-link-alt" title="Live demo"></i>@endif
                        @if($project->source_code_url)<i class="fab fa-github" title="Source code"></i>@endif
                    </span>
                    <span class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadges[$project->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($project->status) }}</span>
                        <span class="text-xs text-gray-500">{{ ($project->date_completed ?? $project->created_at)->format('M Y') }}</span>
                    </span>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                </li>
            @endforeach
        </ul>

        @if($projects->hasPages())
            <div class="p-4 lg:p-6 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-gray-600">
                    Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} results
                </p>
                {{ $projects->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <div class="p-8 lg:p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-project-diagram text-gray-400 text-2xl"></i>
            </div>
            @if($filters || $currentStatus)
                <h3 class="text-lg font-medium text-gray-900 mb-2">No projects found</h3>
                <p class="text-gray-600 mb-4">No projects match your current filters.</p>
                <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Clear Filters
                </a>
            @else
                <h3 class="text-lg font-medium text-gray-900 mb-2">No projects yet</h3>
                <p class="text-gray-600 mb-4">Start building your professional web development portfolio.</p>
                <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Your First Project
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Project quick view -->
<x-admin.slide-over>
    @foreach($projects as $project)
        <div data-item="{{ $project->id }}" class="hidden">
            @if($project->images && count($project->images) > 0)
                <div class="bg-gray-100">
                    <img src="{{ asset($project->images[0]) }}" alt="{{ $project->title }}" class="w-full h-52 object-cover">
                </div>
                @if(count($project->images) > 1)
                    <div class="flex gap-2 px-5 pt-3 overflow-x-auto">
                        @foreach(array_slice($project->images, 1) as $image)
                            <img src="{{ asset($image) }}" alt="" loading="lazy" class="w-16 h-16 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                        @endforeach
                    </div>
                @endif
            @endif

            <div class="p-5 space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadges[$project->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($project->status) }}</span>
                        @if($project->date_completed)
                            <span class="text-xs text-gray-500">Completed {{ $project->date_completed->format('M d, Y') }}</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 break-words">{{ $project->title }}</h2>
                </div>

                @if($project->live_demo_url || $project->source_code_url)
                    <div class="flex gap-2">
                        @if($project->live_demo_url)
                            <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100">
                                <i class="fas fa-external-link-alt mr-2"></i>Live Demo
                            </a>
                        @endif
                        @if($project->source_code_url)
                            <a href="{{ $project->source_code_url }}" target="_blank" rel="noopener"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100">
                                <i class="fab fa-github mr-2"></i>Source Code
                            </a>
                        @endif
                    </div>
                @endif

                @if($project->description)
                    <section>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Description</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $project->description }}</p>
                    </section>
                @endif

                @if($project->skills->count() > 0)
                    <section>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Skills</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($project->skills as $skill)
                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-800 rounded-lg text-xs font-medium">
                                    @if($skill->icon)
                                        <i class="{{ $skill->icon }} mr-1.5" style="color: {{ $skill->color ?? '#6B7280' }}"></i>
                                    @endif
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($project->tags->count() > 0)
                    <section>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">Tags</h3>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($project->tags as $tag)
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.projects.show', $project) }}"
                       class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-eye mr-2"></i>Full Details
                    </a>
                    <a href="{{ route('admin.projects.edit', $project) }}"
                       class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" class="col-span-2"
                          onsubmit="return confirm('Are you sure you want to delete this project?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                            <i class="fas fa-trash mr-2"></i>Delete Project
                        </button>
                    </form>
                </section>
            </div>
        </div>
    @endforeach
</x-admin.slide-over>
@endsection
