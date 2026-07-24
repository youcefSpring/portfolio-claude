@extends('layouts.public')

@section('title', $course->title)

@if($course->description)
    @section('meta_description', Str::limit($course->description, 160))
@endif

@section('content')
<!-- Header -->
<header class="relative overflow-hidden aurora pt-32 pb-16 lg:pt-40 lg:pb-20">
    <div class="absolute inset-0 grid-bg"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm t-low mb-6">
            <a href="{{ url('/') }}" class="hover-hi transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('courses.index') }}" class="hover-hi transition-colors">Courses</a>
            <span>/</span>
            <span class="t-mid">{{ $course->title }}</span>
        </nav>

        <h1 class="text-4xl md:text-6xl font-extrabold t-hi leading-tight mb-6">{{ $course->title }}</h1>

        @if($course->description)
            <p class="text-base md:text-lg t-mid max-w-2xl leading-relaxed mb-8 prose-plain">{{ $course->description }}</p>
        @endif

        <div class="flex flex-wrap gap-3">
            @if($course->link)
                <a href="{{ $course->link }}" target="_blank" rel="noopener"
                   class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-solid transition-all hover:scale-105">
                    <i class="fas fa-external-link-alt mr-2 text-xs"></i>Open course
                </a>
            @endif
            <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-ghost transition-all hover:scale-105">
                <i class="fas fa-arrow-left mr-2 text-xs"></i>All courses
            </a>
        </div>
    </div>
</header>

<!-- Main photo -->
@if($course->image)
    <section class="relative pb-16 lg:pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card rounded-2xl overflow-hidden">
                <img src="{{ $course->image_url }}" alt="{{ $course->title }}"
                     class="w-full max-h-[28rem] object-cover">
            </div>
        </div>
    </section>
@endif

<!-- Other courses -->
@php
    $otherCourses = \App\Models\Course::where('is_published', true)
        ->whereKeyNot($course->id)
        ->latest()
        ->take(3)
        ->get();
@endphp

@if($otherCourses->count())
<section class="relative py-16 lg:py-24 section-line bg-alt">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 mb-10">
            <h2 class="text-2xl md:text-4xl font-bold t-hi">More courses</h2>
            <a href="{{ route('courses.index') }}" class="text-sm font-semibold accent hover:underline">All courses →</a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($otherCourses as $other)
                @php $target = $other->link ?: route('courses.show', $other); @endphp
                <a href="{{ $target }}" @if($other->link) target="_blank" rel="noopener" @endif
                   class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 block">
                    @if($other->image)
                        <div class="h-40 overflow-hidden bg-alt">
                            <img src="{{ $other->image_url }}" alt="{{ $other->title }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-lg font-bold t-hi mb-2">{{ $other->title }}</h3>
                        @if($other->description)
                            <p class="text-sm t-mid leading-relaxed">{{ Str::limit($other->description, 100) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
