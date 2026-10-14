@extends('layouts.public')

@section('title', $jobOffer->title)

@if($jobOffer->description)
    @section('meta_description', Str::limit($jobOffer->description, 160))
@endif

@push('styles')
<style>
    .field {
        background: var(--card);
        border: 1px solid var(--border);
        color: var(--text-hi);
    }
    .field:focus { outline: none; border-color: var(--border-strong); }
    .field-error { border-color: #ef4444; }
</style>
@endpush

@section('content')
<!-- Header -->
<header class="relative overflow-hidden aurora pt-24 pb-8 lg:pt-28 lg:pb-10">
    <div class="absolute inset-0 grid-bg"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs t-low mb-3">
            <a href="{{ url('/') }}" class="hover-hi transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('jobs.index') }}" class="hover-hi transition-colors">Work With Me</a>
            <span>/</span>
            <span class="t-mid">{{ $jobOffer->title }}</span>
        </nav>

        @if($jobOffer->featured)
            <span class="inline-block mb-2 px-2.5 py-1 text-[11px] font-semibold rounded-full pill">
                <i class="fas fa-star mr-1"></i>Featured
            </span>
        @endif

        <h1 class="text-3xl md:text-4xl font-extrabold t-hi leading-tight mb-3">{{ $jobOffer->title }}</h1>

        <div class="flex flex-wrap gap-2 mb-2">
            <span class="px-3 py-1 text-xs font-semibold rounded-full pill"><i class="fas fa-briefcase mr-1"></i>{{ ucfirst($jobOffer->project_type) }}</span>
            <span class="px-3 py-1 text-xs font-semibold rounded-full pill"><i class="fas fa-location-dot mr-1"></i>{{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}</span>
            @if($jobOffer->duration)
                <span class="px-3 py-1 text-xs font-semibold rounded-full pill"><i class="fas fa-clock mr-1"></i>{{ $jobOffer->duration }}</span>
            @endif
            @if($jobOffer->budget_min || $jobOffer->budget_max)
                <span class="px-3 py-1 text-xs font-semibold rounded-full pill"><i class="fas fa-dollar-sign mr-1"></i>{{ $jobOffer->budget_range }}</span>
            @endif
        </div>

        <p class="text-xs t-low">Posted {{ $jobOffer->published_at->diffForHumans() }}</p>
    </div>
</header>

<!-- Main -->
<section class="relative pb-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @if(session('success'))
                <div class="card rounded-2xl p-4 text-sm flex items-center gap-3" style="border-color: rgba(34,197,94,.5)">
                    <i class="fas fa-circle-check text-green-500"></i>
                    <p class="t-hi">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="card rounded-2xl p-4 text-sm flex items-center gap-3" style="border-color: rgba(239,68,68,.5)">
                    <i class="fas fa-circle-exclamation text-red-500"></i>
                    <p class="t-hi">{{ session('error') }}</p>
                </div>
            @endif

            @if($jobOffer->images && count($jobOffer->images) > 0)
                <div class="card rounded-2xl p-6">
                    <h2 class="text-xl font-bold t-hi mb-4">Screenshots & mockups</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($jobOffer->images as $image)
                            <button type="button" class="group overflow-hidden rounded-xl border border-base"
                                    onclick="openModal('{{ asset('storage/' . $image) }}')">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $jobOffer->title }}" loading="lazy"
                                     class="w-full h-48 md:h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card rounded-2xl p-6">
                <h2 class="text-xl font-bold t-hi mb-4">About this opportunity</h2>
                <div class="text-sm t-mid prose-plain">{{ $jobOffer->description }}</div>
            </div>

            @if($jobOffer->requirements)
                <div class="card rounded-2xl p-6">
                    <h2 class="text-xl font-bold t-hi mb-4">Requirements</h2>
                    <div class="text-sm t-mid prose-plain">{{ $jobOffer->requirements }}</div>
                </div>
            @endif

            @if($jobOffer->skills->count())
                <div class="card rounded-2xl p-6">
                    <h2 class="text-xl font-bold t-hi mb-4">Required skills</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($jobOffer->skills as $skill)
                            <span class="px-3 py-1.5 text-sm font-medium rounded-lg pill">
                                @if($skill->icon)
                                    <i class="fas {{ $skill->icon }} mr-1.5 text-xs"></i>
                                @endif
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Application form -->
            <div id="apply" class="card rounded-2xl p-6">
                <h2 class="text-xl font-bold t-hi mb-6">Apply for this position</h2>

                <form action="{{ route('jobs.apply', $jobOffer) }}" method="POST" enctype="multipart/form-data" id="applicationForm" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="full_name" class="block text-xs font-semibold t-hi mb-1.5">Full name <span class="text-red-500">*</span></label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                   placeholder="Enter your full name"
                                   class="field w-full px-4 py-2.5 text-sm rounded-xl @error('full_name') field-error @enderror">
                            @error('full_name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold t-hi mb-1.5">Email address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="your.email@example.com"
                                   class="field w-full px-4 py-2.5 text-sm rounded-xl @error('email') field-error @enderror">
                            @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold t-hi mb-1.5">Phone number <span class="text-red-500">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                   placeholder="0555 12 34 56"
                                   class="field w-full px-4 py-2.5 text-sm rounded-xl @error('phone') field-error @enderror">
                            <p class="mt-1 text-xs t-low">Algerian number (+213 or 0…)</p>
                            @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="cv_file" class="block text-xs font-semibold t-hi mb-1.5">CV / Resume <span class="text-red-500">*</span></label>
                            <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" required
                                   class="field w-full px-4 py-2 text-sm rounded-xl @error('cv_file') field-error @enderror">
                            <p class="mt-1 text-xs t-low">PDF, DOC, DOCX (max 5MB)</p>
                            @error('cv_file')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach([
                            ['github_url', 'GitHub', 'fab fa-github', 'url', 'https://github.com/username'],
                            ['linkedin_url', 'LinkedIn', 'fab fa-linkedin', 'url', 'https://linkedin.com/in/username'],
                            ['telegram', 'Telegram', 'fab fa-telegram', 'text', '@username'],
                            ['portfolio_url', 'Portfolio', 'fas fa-globe', 'url', 'https://your-portfolio.com'],
                        ] as [$name, $label, $icon, $type, $placeholder])
                            <div>
                                <label for="{{ $name }}" class="block text-xs font-semibold t-hi mb-1.5">
                                    <i class="{{ $icon }} mr-1"></i>{{ $label }} <span class="t-low font-normal">(optional)</span>
                                </label>
                                <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name) }}"
                                       placeholder="{{ $placeholder }}"
                                       class="field w-full px-4 py-2.5 text-sm rounded-xl @error($name) field-error @enderror">
                                @error($name)<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>

                    <div>
                        <label for="cover_letter" class="block text-xs font-semibold t-hi mb-1.5">Cover letter <span class="t-low font-normal">(optional)</span></label>
                        <textarea id="cover_letter" name="cover_letter" rows="5"
                                  placeholder="Tell us why you're interested in this opportunity..."
                                  class="field w-full px-4 py-2.5 text-sm rounded-xl @error('cover_letter') field-error @enderror">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full px-6 py-3 text-sm font-semibold rounded-full btn-solid transition-all">
                        <i class="fas fa-paper-plane mr-2"></i>Submit application
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <div class="card rounded-2xl p-6 lg:sticky lg:top-24">
                <h3 class="text-base font-bold t-hi mb-4">Quick information</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="t-low">Project type</dt><dd class="t-hi font-medium text-right">{{ ucfirst($jobOffer->project_type) }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="t-low">Location type</dt><dd class="t-hi font-medium text-right">{{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}</dd></div>
                    @if($jobOffer->location)
                        <div class="flex justify-between gap-4"><dt class="t-low">Location</dt><dd class="t-hi font-medium text-right">{{ $jobOffer->location }}</dd></div>
                    @endif
                    @if($jobOffer->duration)
                        <div class="flex justify-between gap-4"><dt class="t-low">Duration</dt><dd class="t-hi font-medium text-right">{{ $jobOffer->duration }}</dd></div>
                    @endif
                    @if($jobOffer->budget_min || $jobOffer->budget_max)
                        <div class="flex justify-between gap-4"><dt class="t-low">Budget</dt><dd class="accent font-semibold text-right">{{ $jobOffer->budget_range }}</dd></div>
                    @endif
                    <div class="flex justify-between gap-4"><dt class="t-low">Posted</dt><dd class="t-hi font-medium text-right">{{ $jobOffer->published_at->format('M d, Y') }}</dd></div>
                </dl>
                <a href="#apply" class="mt-6 block text-center px-6 py-3 text-sm font-semibold rounded-full btn-solid transition-all">Apply now</a>
            </div>

            @if($relatedJobs->count())
                <div class="card rounded-2xl p-6">
                    <h3 class="text-base font-bold t-hi mb-4">Related opportunities</h3>
                    <div class="space-y-4">
                        @foreach($relatedJobs as $related)
                            <a href="{{ route('jobs.show', $related) }}" class="block group">
                                <h4 class="text-sm font-semibold t-hi group-hover:underline mb-1.5">{{ $related->title }}</h4>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full pill">{{ ucfirst($related->project_type) }}</span>
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full pill">{{ ucfirst(str_replace('-', ' ', $related->location_type)) }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</section>

<!-- Image modal -->
<div id="imageModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/90 p-4" onclick="closeModal()">
    <button type="button" aria-label="Close" class="absolute top-6 right-6 text-white text-3xl hover:text-gray-300 transition-colors">
        <i class="fas fa-xmark"></i>
    </button>
    <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full rounded shadow-2xl object-contain">
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('cv_file').addEventListener('change', function () {
        const file = this.files[0];
        if (file && file.size > 5 * 1024 * 1024) {
            alert('File size must not exceed 5MB. Please choose a smaller file.');
            this.value = '';
        }
    });

    document.getElementById('applicationForm').addEventListener('submit', function (e) {
        if (!confirm('Are you sure you want to submit this application? Please review your information before proceeding.')) {
            e.preventDefault();
        }
    });

    function openModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endpush
