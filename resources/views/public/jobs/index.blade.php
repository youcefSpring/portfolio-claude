@extends('layouts.public')

@section('title', 'Work With Me')

@push('styles')
<style>
    .field {
        background: var(--card);
        border: 1px solid var(--border);
        color: var(--text-hi);
    }
    .field:focus { outline: none; border-color: var(--border-strong); }
    .field option { background: var(--bg); color: var(--text-hi); }
</style>
@endpush

@section('content')
<!-- Header -->
<header class="relative overflow-hidden aurora pt-24 pb-8 lg:pt-28 lg:pb-10">
    <div class="absolute inset-0 grid-bg"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Opportunities</span>
        <h1 class="mt-1 text-3xl md:text-4xl font-extrabold t-hi">Work With Me</h1>
        <p class="mt-2 text-sm t-mid max-w-2xl leading-relaxed">
            Current consulting and freelance opportunities. I'm always open to projects and collaborations that challenge and inspire.
        </p>

        <!-- Filters -->
        <form method="GET" action="{{ route('jobs.index') }}" class="mt-6 grid grid-cols-1 md:grid-cols-12 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search titles, descriptions..."
                   aria-label="Search opportunities"
                   class="field md:col-span-5 px-4 py-2.5 text-sm rounded-xl transition-colors">

            <select name="type" aria-label="Project type" class="field md:col-span-3 px-4 py-2.5 text-sm rounded-xl transition-colors">
                <option value="">All types</option>
                @foreach($projectTypes as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') == $key)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="location" aria-label="Location" class="field md:col-span-2 px-4 py-2.5 text-sm rounded-xl transition-colors">
                <option value="">All locations</option>
                @foreach($locationTypes as $key => $label)
                    <option value="{{ $key }}" @selected(request('location') == $key)>{{ $label }}</option>
                @endforeach
            </select>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl btn-solid transition-all">Filter</button>
                @if(request()->hasAny(['search', 'type', 'location']))
                    <a href="{{ route('jobs.index') }}" aria-label="Clear filters"
                       class="px-4 py-2.5 text-sm rounded-xl btn-ghost transition-all flex items-center">
                        <i class="fas fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</header>

<!-- Grid -->
<section class="relative pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($jobOffers->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobOffers as $job)
                    <a href="{{ route('jobs.show', $job) }}"
                       class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 flex flex-col">
                        @if($job->images && count($job->images) > 0)
                            <div class="relative h-44 overflow-hidden bg-alt">
                                <img src="{{ asset('storage/' . $job->images[0]) }}" alt="{{ $job->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @if($job->featured)
                                    <span class="absolute top-3 right-3 px-2.5 py-1 text-[11px] font-semibold rounded-full btn-solid">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                @endif
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">
                            @if($job->featured && !($job->images && count($job->images) > 0))
                                <span class="self-start mb-3 px-2.5 py-1 text-[11px] font-semibold rounded-full pill">
                                    <i class="fas fa-star mr-1"></i>Featured
                                </span>
                            @endif

                            @if($job->isClosed())
                                <span class="self-start mb-3 px-2.5 py-1 text-[11px] font-semibold rounded-full bg-red-500/10 text-red-500">
                                    <i class="fas fa-lock mr-1"></i>Closed
                                </span>
                            @endif

                            <h2 class="text-lg font-bold t-hi mb-3">{{ $job->title }}</h2>

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ ucfirst($job->project_type) }}</span>
                                <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ ucfirst(str_replace('-', ' ', $job->location_type)) }}</span>
                            </div>

                            <p class="text-sm t-mid leading-relaxed mb-4">{{ Str::limit($job->description, 120) }}</p>

                            @if($job->skills->count())
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach($job->skills->take(3) as $skill)
                                        <span class="px-2 py-0.5 text-xs rounded-md border border-base t-mid">{{ $skill->name }}</span>
                                    @endforeach
                                    @if($job->skills->count() > 3)
                                        <span class="px-2 py-0.5 text-xs rounded-md border border-base t-low">+{{ $job->skills->count() - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-auto flex flex-wrap items-center gap-x-4 gap-y-1 text-xs t-low mb-4">
                                @if($job->budget_min || $job->budget_max)
                                    <span class="font-semibold accent"><i class="fas fa-dollar-sign mr-1"></i>{{ $job->budget_range }}</span>
                                @endif
                                @if($job->duration)
                                    <span><i class="fas fa-clock mr-1"></i>{{ $job->duration }}</span>
                                @endif
                                <span>Posted {{ $job->published_at->diffForHumans() }}</span>
                            </div>

                            <span class="inline-flex items-center text-sm font-semibold accent">
                                {{ $job->isClosed() ? 'View details' : 'View details & apply' }}
                                <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($jobOffers->hasPages())
                <div class="mt-12">{{ $jobOffers->withQueryString()->links() }}</div>
            @endif
        @else
            <div class="card rounded-2xl p-12 text-center">
                <i class="fas fa-briefcase text-4xl accent mb-4"></i>
                <h2 class="text-xl font-bold t-hi mb-2">No opportunities found</h2>
                <p class="t-mid text-sm">
                    @if(request()->hasAny(['search', 'type', 'location']))
                        Try adjusting your filters or <a href="{{ route('jobs.index') }}" class="accent font-semibold hover:underline">clear all filters</a>.
                    @else
                        There are no active offers at the moment. Check back soon.
                    @endif
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
