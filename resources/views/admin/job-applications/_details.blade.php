<div class="flex items-start gap-4">
    <span class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold">
        {{ Str::upper(Str::substr($application->full_name, 0, 1)) }}
    </span>
    <div class="min-w-0">
        <h2 class="text-xl font-bold text-gray-900 break-words">{{ $application->full_name }}</h2>
        <div class="flex items-center gap-2 mt-1">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $application->status_color }}-100 text-{{ $application->status_color }}-800">{{ $application->status_label }}</span>
            <span class="text-xs text-gray-500">Applied {{ $application->applied_at->format('M d, Y') }}</span>
        </div>
        @if(! empty($showJob) && $application->jobOffer)
            <a href="{{ route('admin.job-offers.show', $application->jobOffer) }}" class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-700">
                <i class="fas fa-briefcase mr-1.5"></i>{{ $application->jobOffer->title }}
            </a>
        @endif
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
            @if(Str::lower(pathinfo($application->cv_path, PATHINFO_EXTENSION)) === 'pdf')
                {{-- PDFs preview inline; the iframe only loads on first open --}}
                <button type="button" data-cv-toggle
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-file-pdf mr-2"></i><span data-cv-label>View CV</span>
                </button>
            @else
                <a href="{{ $application->cv_url }}" target="_blank"
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-file-word mr-2"></i>View CV
                </a>
            @endif
            <a href="{{ route('admin.job-applications.download-cv', $application) }}"
               class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-download mr-2"></i>Download
            </a>
        </div>
        <div data-cv-frame class="hidden mt-3">
            <iframe data-src="{{ $application->cv_url }}" title="CV of {{ $application->full_name }}"
                    class="w-full h-[75vh] rounded-lg border border-gray-200 bg-gray-50"></iframe>
            <a href="{{ $application->cv_url }}" target="_blank" rel="noopener" class="inline-flex items-center mt-2 text-xs text-gray-500 hover:text-blue-600">
                <i class="fas fa-external-link-alt mr-1.5"></i>Open in new tab
            </a>
        </div>
    </section>

    @once
    @push('scripts')
    <script>
    // Toggle the inline CV preview and widen the slide-over while it's showing
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('[data-cv-toggle]');
        if (!toggle) return;
        const section = toggle.closest('section');
        const frame = section.querySelector('[data-cv-frame]');
        const iframe = frame.querySelector('iframe');
        const opening = frame.classList.contains('hidden');
        if (opening && !iframe.src) iframe.src = iframe.dataset.src;
        frame.classList.toggle('hidden', !opening);
        toggle.querySelector('[data-cv-label]').textContent = opening ? 'Hide CV' : 'View CV';
        const panelBody = toggle.closest('[data-panel-body], #applicant-panel-body');
        if (panelBody) {
            panelBody.classList.toggle('max-w-lg', !opening);
            panelBody.classList.toggle('max-w-3xl', opening);
        }
    });
    </script>
    @endpush
    @endonce
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
            @foreach(\App\Models\JobApplication::STATUSES as $key => $label)
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

@if(! empty($showDelete))
    <section class="pt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('admin.job-applications.destroy', $application) }}"
              onsubmit="return confirm('Delete this application? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete Application
            </button>
        </form>
    </section>
@endif
