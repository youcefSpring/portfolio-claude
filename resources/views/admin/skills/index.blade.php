@extends('layouts.admin-modern')

@section('title', 'Skills Management')
@section('page-title', 'Skills Management')

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
    $iconCdn = 'https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/';
@endphp

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 lg:mb-8">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Skills Management</h1>
        <p class="text-gray-600 mt-1">Name, logo and featured flag — edited inline</p>
    </div>
    <button type="button" data-skill-add
            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i>Add New Skill
    </button>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
    </div>
@endif

<!-- Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
    <div class="p-4 lg:p-6">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search skills by name..."
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <button type="submit" class="px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                <i class="fas fa-search"></i>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.skills.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Skills grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($skills as $skill)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 flex items-center justify-center bg-gray-50 border border-gray-100 rounded-xl overflow-hidden">
                    @if($skill->logo)
                        <img src="{{ $skill->logo_url }}" alt="{{ $skill->name }}" class="w-8 h-8 object-contain">
                    @elseif($skill->simple_icon)
                        <img src="{{ $iconCdn . $skill->simple_icon }}.svg" alt="{{ $skill->name }}" class="w-7 h-7 object-contain">
                    @elseif($skill->icon)
                        <i class="{{ $skill->icon }} text-blue-600"></i>
                    @else
                        <i class="fas fa-code text-gray-400"></i>
                    @endif
                </div>

                @if($skill->is_featured)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-star mr-1"></i>Featured
                    </span>
                @endif
            </div>

            <h3 class="text-base font-semibold text-gray-900 mb-4">{{ $skill->name }}</h3>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button"
                        data-skill-edit
                        data-id="{{ $skill->id }}"
                        data-name="{{ $skill->name }}"
                        data-simple-icon="{{ $skill->simple_icon }}"
                        data-logo="{{ $skill->logo ? $skill->logo_url : '' }}"
                        data-featured="{{ $skill->is_featured ? 1 : 0 }}"
                        data-action="{{ route('admin.skills.update', $skill) }}"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-edit mr-1"></i>Edit
                </button>

                <form method="POST" action="{{ route('admin.skills.destroy', $skill) }}"
                      onsubmit="return confirm('Delete “{{ $skill->name }}”?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                        <i class="fas fa-trash mr-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-code text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No skills found</h3>
            <p class="text-gray-500 mb-6">Get started by adding your first skill.</p>
            <button type="button" data-skill-add class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add First Skill
            </button>
        </div>
    @endforelse
</div>

@if($skills->hasPages())
    <div class="mt-8">{{ $skills->links() }}</div>
@endif

<!-- Add / Edit modal -->
<div id="skill-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/60" data-skill-close></div>

    <div class="relative min-h-full flex items-start sm:items-center justify-center p-4">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl my-8">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 id="skill-modal-title" class="text-lg font-semibold text-gray-900">Add Skill</h2>
                <button type="button" data-skill-close class="p-2 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="skill-form" method="POST" action="{{ route('admin.skills.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="skill-method" value="POST">

                <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">
                    <!-- Name -->
                    <div>
                        <label for="skill-name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="skill-name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>

                        <div class="flex items-center gap-4 mb-4">
                            <div id="skill-logo-preview" class="w-16 h-16 flex items-center justify-center bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                                <i class="fas fa-image text-xl text-gray-300"></i>
                            </div>
                            <div class="text-sm text-gray-500">
                                <p>Pick a logo below, or upload your own.</p>
                                <p id="skill-logo-name" class="font-medium text-gray-700">—</p>
                            </div>
                        </div>

                        <input type="hidden" name="simple_icon" id="skill-simple-icon" value="">

                        <input type="text" id="skill-logo-search" placeholder="Search logos (php, react, docker…)"
                               class="w-full px-3 py-2 mb-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">

                        <div class="grid grid-cols-6 sm:grid-cols-9 gap-2 max-h-56 overflow-y-auto p-2 border border-gray-200 rounded-lg bg-gray-50">
                            @foreach($iconChoices as $slug)
                                <button type="button" data-logo-option data-slug="{{ $slug }}" title="{{ $slug }}"
                                        class="flex items-center justify-center p-2 rounded-lg border border-transparent bg-white hover:border-blue-300 transition-colors">
                                    <img src="{{ $iconCdn . $slug }}.svg" alt="{{ $slug }}" loading="lazy" class="w-6 h-6 object-contain">
                                </button>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label for="skill-logo" class="block text-sm font-medium text-gray-700 mb-2">Or upload a custom logo</label>
                            <input type="file" id="skill-logo" name="logo" accept="image/jpeg,image/jpg,image/png,image/gif,image/svg+xml,image/webp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <p class="mt-1 text-xs text-gray-500">PNG, SVG, JPG or WEBP — max 2MB. Overrides the picked logo.</p>
                            <label id="skill-remove-logo-wrap" class="hidden items-center mt-2 text-sm text-gray-700">
                                <input type="checkbox" name="remove_logo" value="1" id="skill-remove-logo" class="rounded border-gray-300 text-blue-600">
                                <span class="ml-2">Remove current logo</span>
                            </label>
                        </div>
                    </div>

                    <!-- Featured -->
                    <label class="flex items-center gap-3 p-4 bg-gray-50 border border-gray-200 rounded-lg cursor-pointer">
                        <input type="checkbox" id="skill-featured" name="is_featured" value="1"
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span>
                            <span class="block text-sm font-medium text-gray-900">Featured skill</span>
                            <span class="block text-xs text-gray-500">Featured skills appear on the public homepage</span>
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" data-skill-close class="px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">Cancel</button>
                    <button type="submit" class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-2"></i><span id="skill-submit-label">Create Skill</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const iconCdn = @json($iconCdn);
    const storeAction = @json(route('admin.skills.store'));

    const modal = document.getElementById('skill-modal');
    const form = document.getElementById('skill-form');
    const method = document.getElementById('skill-method');
    const title = document.getElementById('skill-modal-title');
    const submitLabel = document.getElementById('skill-submit-label');
    const nameInput = document.getElementById('skill-name');
    const simpleIcon = document.getElementById('skill-simple-icon');
    const preview = document.getElementById('skill-logo-preview');
    const previewName = document.getElementById('skill-logo-name');
    const featured = document.getElementById('skill-featured');
    const upload = document.getElementById('skill-logo');
    const removeWrap = document.getElementById('skill-remove-logo-wrap');
    const removeLogo = document.getElementById('skill-remove-logo');
    const options = Array.from(document.querySelectorAll('[data-logo-option]'));
    const search = document.getElementById('skill-logo-search');

    const ACTIVE = 'border-blue-500 bg-blue-50';
    const IDLE = 'border-transparent bg-white hover:border-blue-300';

    function highlight(slug) {
        options.forEach(o => {
            const on = o.dataset.slug === slug;
            o.className = o.className.replace(ACTIVE, IDLE);
            if (on) o.className = o.className.replace(IDLE, ACTIVE);
        });
    }

    function showImage(src, label) {
        preview.innerHTML = `<img src="${src}" alt="" class="w-10 h-10 object-contain">`;
        previewName.textContent = label || '—';
    }

    function resetLogo() {
        preview.innerHTML = '<i class="fas fa-image text-xl text-gray-300"></i>';
        previewName.textContent = '—';
    }

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        nameInput.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Add
    document.querySelectorAll('[data-skill-add]').forEach(btn => btn.addEventListener('click', function () {
        form.action = storeAction;
        method.value = 'POST';
        title.textContent = 'Add Skill';
        submitLabel.textContent = 'Create Skill';
        form.reset();
        simpleIcon.value = '';
        highlight(null);
        resetLogo();
        removeWrap.classList.add('hidden');
        removeWrap.classList.remove('flex');
        openModal();
    }));

    // Edit
    document.querySelectorAll('[data-skill-edit]').forEach(btn => btn.addEventListener('click', function () {
        const data = this.dataset;
        form.action = data.action;
        method.value = 'PUT';
        title.textContent = 'Edit Skill';
        submitLabel.textContent = 'Save Changes';
        form.reset();

        nameInput.value = data.name;
        featured.checked = data.featured === '1';
        simpleIcon.value = data.simpleIcon || '';
        highlight(data.simpleIcon || null);

        if (data.logo) {
            showImage(data.logo, 'custom logo');
            removeWrap.classList.remove('hidden');
            removeWrap.classList.add('flex');
        } else {
            removeWrap.classList.add('hidden');
            removeWrap.classList.remove('flex');
            data.simpleIcon ? showImage(`${iconCdn}${data.simpleIcon}.svg`, data.simpleIcon) : resetLogo();
        }

        openModal();
    }));

    // Close
    document.querySelectorAll('[data-skill-close]').forEach(el => el.addEventListener('click', closeModal));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
    });

    // Logo picking
    options.forEach(button => button.addEventListener('click', function () {
        const slug = this.dataset.slug;
        simpleIcon.value = slug;
        upload.value = '';
        if (removeLogo) removeLogo.checked = false;
        showImage(`${iconCdn}${slug}.svg`, slug);
        highlight(slug);
    }));

    search.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();
        options.forEach(o => o.classList.toggle('hidden', term && !o.dataset.slug.includes(term)));
    });

    upload.addEventListener('change', function () {
        if (!this.files[0]) return;
        showImage(URL.createObjectURL(this.files[0]), this.files[0].name);
    });

    // Reopen the modal with the submitted values when validation fails
    @if($errors->any())
        document.querySelector('[data-skill-add]').click();
        nameInput.value = @json(old('name'));
        @if(old('simple_icon'))
            simpleIcon.value = @json(old('simple_icon'));
            highlight(simpleIcon.value);
            showImage(`${iconCdn}${simpleIcon.value}.svg`, simpleIcon.value);
        @endif
        featured.checked = {{ old('is_featured') ? 'true' : 'false' }};
    @endif
});
</script>
@endpush
