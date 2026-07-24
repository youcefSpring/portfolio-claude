@extends('layouts.public')

@section('title', 'Courses')

@section('content')
<!-- Header -->
<header class="relative overflow-hidden aurora pt-32 pb-16 lg:pt-40 lg:pb-20">
    <div class="absolute inset-0 grid-bg"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Teaching</span>
        <h1 class="mt-2 text-4xl md:text-6xl font-extrabold t-hi">Courses</h1>
        <p class="mt-4 text-sm t-low">{{ $courses->total() }} {{ Str::plural('course', $courses->total()) }}</p>
    </div>
</header>

<!-- Grid -->
<section class="relative pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($courses->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    @php $target = $course->link ?: route('courses.show', $course); @endphp
                    <a href="{{ $target }}" @if($course->link) target="_blank" rel="noopener" @endif
                       class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 block">
                        @if($course->image)
                            <div class="h-40 overflow-hidden bg-alt">
                                <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        @endif

                        <div class="p-6">
                            <h2 class="text-lg font-bold t-hi mb-2">{{ $course->title }}</h2>
                            @if($course->description)
                                <p class="text-sm t-mid leading-relaxed mb-4">{{ Str::limit($course->description, 110) }}</p>
                            @endif
                            <span class="inline-flex items-center text-sm font-semibold accent">
                                {{ $course->link ? 'Open course' : 'View details' }}
                                <i class="fas fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($courses->hasPages())
                <div class="mt-12">{{ $courses->links() }}</div>
            @endif
        @else
            <div class="card rounded-2xl p-12 text-center">
                <i class="fas fa-book-open text-4xl accent mb-4"></i>
                <h2 class="text-xl font-bold t-hi mb-2">No courses published yet</h2>
                <p class="t-mid text-sm">Check back soon.</p>
            </div>
        @endif
    </div>
</section>
@endsection
