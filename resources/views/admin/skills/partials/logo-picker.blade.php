@php
    $iconChoices = [
        'php', 'laravel', 'javascript', 'typescript', 'nodedotjs', 'react', 'vuedotjs', 'angular',
        'nextdotjs', 'nuxt', 'svelte', 'tailwindcss', 'bootstrap', 'html5', 'css3', 'sass',
        'python', 'django', 'flask', 'java', 'spring', 'kotlin', 'swift', 'dart', 'flutter',
        'go', 'rust', 'c', 'cplusplus', 'csharp', 'dotnet', 'ruby', 'rubyonrails',
        'mysql', 'postgresql', 'sqlite', 'mongodb', 'redis', 'elasticsearch', 'firebase', 'supabase',
        'docker', 'kubernetes', 'git', 'github', 'gitlab', 'linux', 'ubuntu', 'nginx', 'apache',
        'amazonaws', 'googlecloud', 'microsoftazure', 'vercel', 'netlify',
        'figma', 'adobephotoshop', 'adobeillustrator', 'blender',
        'openai', 'tensorflow', 'pytorch', 'pandas', 'numpy', 'jupyter',
        'graphql', 'postman', 'jira', 'slack', 'notion', 'wordpress', 'shopify',
    ];
    $current = old('simple_icon', $skill->simple_icon ?? '');
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>

    <!-- Selected logo -->
    <div class="flex items-center gap-4 mb-4">
        <div id="logo-selected" class="w-20 h-20 flex items-center justify-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
            @if(isset($skill) && $skill->logo)
                <img src="{{ $skill->logo_url }}" alt="" class="w-12 h-12 object-contain">
            @elseif($current)
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/{{ $current }}.svg" alt="" class="w-12 h-12 object-contain">
            @else
                <i class="fas fa-image text-2xl text-gray-300"></i>
            @endif
        </div>
        <div class="text-sm text-gray-500">
            <p>Pick a logo below, or upload your own.</p>
            <p id="logo-selected-name" class="font-medium text-gray-700">{{ $current ?: '—' }}</p>
        </div>
    </div>

    <input type="hidden" id="simple_icon" name="simple_icon" value="{{ $current }}">
    @error('simple_icon')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <!-- Search -->
    <input type="text" id="logo-search" placeholder="Search logos (php, react, docker…)"
           class="w-full px-3 py-2 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">

    <!-- Grid -->
    <div id="logo-grid" class="grid grid-cols-5 sm:grid-cols-8 gap-2 max-h-72 overflow-y-auto p-2 border border-gray-200 rounded-lg bg-gray-50">
        @foreach($iconChoices as $slug)
            <button type="button"
                    class="logo-option group flex flex-col items-center justify-center p-2 rounded-lg border transition-colors {{ $current === $slug ? 'border-blue-500 bg-blue-50' : 'border-transparent bg-white hover:border-blue-300' }}"
                    data-slug="{{ $slug }}" title="{{ $slug }}">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/{{ $slug }}.svg"
                     alt="{{ $slug }}" loading="lazy" class="w-7 h-7 object-contain">
            </button>
        @endforeach
    </div>

    <!-- Custom upload -->
    <div class="mt-4">
        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Or upload a custom logo</label>
        @if(isset($skill) && $skill->logo)
            <label class="inline-flex items-center mb-2 text-sm text-gray-700">
                <input type="checkbox" name="remove_logo" value="1" class="rounded border-gray-300 text-blue-600">
                <span class="ml-2">Remove current logo</span>
            </label>
        @endif
        <input type="file" id="logo" name="logo" accept="image/jpeg,image/jpg,image/png,image/gif,image/svg+xml,image/webp"
               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('logo') border-red-500 @enderror">
        <p class="mt-1 text-xs text-gray-500">PNG, SVG, JPG or WEBP — max 2MB. Overrides the picked logo.</p>
        @error('logo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hidden = document.getElementById('simple_icon');
    const selected = document.getElementById('logo-selected');
    const selectedName = document.getElementById('logo-selected-name');
    const options = Array.from(document.querySelectorAll('.logo-option'));
    const search = document.getElementById('logo-search');
    const upload = document.getElementById('logo');

    function markActive(button) {
        options.forEach(o => o.className = o.className
            .replace('border-blue-500 bg-blue-50', 'border-transparent bg-white hover:border-blue-300'));
        button.className = button.className
            .replace('border-transparent bg-white hover:border-blue-300', 'border-blue-500 bg-blue-50');
    }

    options.forEach(button => button.addEventListener('click', function () {
        const slug = this.dataset.slug;
        hidden.value = slug;
        selectedName.textContent = slug;
        selected.innerHTML = `<img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/${slug}.svg" alt="" class="w-12 h-12 object-contain">`;
        markActive(this);
    }));

    search.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        options.forEach(o => o.classList.toggle('hidden', term && !o.dataset.slug.includes(term)));
    });

    upload.addEventListener('change', function () {
        if (!this.files[0]) return;
        selected.innerHTML = `<img src="${URL.createObjectURL(this.files[0])}" alt="" class="w-12 h-12 object-contain">`;
        selectedName.textContent = this.files[0].name;
    });
});
</script>
@endpush
