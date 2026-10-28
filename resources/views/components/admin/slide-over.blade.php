{{--
    Right-hand slide-over panel for list pages.

    Rows open it with data-open-item="{id}"; the slot holds one
    <div data-item="{id}" class="hidden"> per row. Prev/next walks the
    visible rows, and the open item is restored after a form inside it
    submits and redirects back.
--}}
@props(['id' => 'slide-over'])

<div id="{{ $id }}" data-slide-over class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div data-close-panel class="absolute inset-0 bg-gray-900/40"></div>
    <div data-panel-body class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-xl flex flex-col translate-x-full transition-transform duration-200">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <button type="button" data-step="-1" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50" title="Previous (↑)"><i class="fas fa-chevron-up text-xs"></i></button>
                <button type="button" data-step="1" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50" title="Next (↓)"><i class="fas fa-chevron-down text-xs"></i></button>
                <span data-position class="text-xs text-gray-500 ml-1"></span>
            </div>
            <button type="button" data-close-panel class="w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100" title="Close (Esc)"><i class="fas fa-times"></i></button>
        </div>
        <div class="flex-1 overflow-y-auto">
            {{ $slot }}
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
document.querySelectorAll('[data-slide-over]').forEach(function (panel) {
    const body = panel.querySelector('[data-panel-body]');
    const position = panel.querySelector('[data-position]');
    const rows = [...document.querySelectorAll('[data-open-item]')];
    const storageKey = 'slideOver:' + location.pathname;
    let currentId = null;

    const visibleIds = () => rows.filter(r => !r.closest('.hidden')).map(r => r.dataset.openItem);

    function open(id) {
        currentId = id;
        panel.querySelectorAll('[data-item]').forEach(el => el.classList.toggle('hidden', el.dataset.item !== id));
        rows.forEach(r => r.classList.toggle('bg-blue-50', r.dataset.openItem === id));
        const ids = visibleIds();
        position.textContent = ids.includes(id) ? `${ids.indexOf(id) + 1} of ${ids.length}` : '';
        panel.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => body.classList.remove('translate-x-full'));
    }
    function close() {
        body.classList.add('translate-x-full');
        setTimeout(() => panel.classList.add('hidden'), 200);
        document.body.style.overflow = '';
        rows.forEach(r => r.classList.remove('bg-blue-50'));
        currentId = null;
    }
    function step(dir) {
        const ids = visibleIds();
        const i = ids.indexOf(currentId);
        if (i !== -1) open(ids[(i + dir + ids.length) % ids.length]);
    }

    rows.forEach(r => r.addEventListener('click', e => {
        // Let checkboxes, links and buttons inside a row do their own thing
        const control = e.target.closest('a, button, input, label, select, form');
        if (control && control !== r && r.contains(control)) return;
        open(r.dataset.openItem);
    }));
    panel.querySelectorAll('[data-close-panel]').forEach(b => b.addEventListener('click', close));
    panel.querySelectorAll('[data-step]').forEach(b => b.addEventListener('click', () => step(+b.dataset.step)));
    panel.querySelectorAll('form').forEach(f => f.addEventListener('submit', () => {
        try { sessionStorage.setItem(storageKey, currentId); } catch (e) {}
    }));
    try {
        const saved = sessionStorage.getItem(storageKey);
        sessionStorage.removeItem(storageKey);
        if (saved && panel.querySelector(`[data-item="${saved}"]`)) open(saved);
    } catch (e) {}

    document.addEventListener('keydown', e => {
        if (!currentId || e.target.matches('textarea, input, select')) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { e.preventDefault(); step(1); }
        if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { e.preventDefault(); step(-1); }
    });
});
</script>
@endpush
@endonce
