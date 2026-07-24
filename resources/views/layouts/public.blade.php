<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Portfolio'))</title>

    @hasSection('meta_description')
        <meta name="description" content="@yield('meta_description')">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Theme must resolve before first paint to avoid a flash -->
    <script>
        (function () {
            const stored = localStorage.getItem('theme');
            const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            document.documentElement.classList.add(stored || (prefersLight ? 'light' : 'dark'));
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root,
        html.dark {
            --bg: #0b0b12;
            --bg-alt: rgba(255, 255, 255, .015);
            --card: rgba(255, 255, 255, .04);
            --card-hover: rgba(255, 255, 255, .07);
            --border: rgba(255, 255, 255, .09);
            --border-strong: rgba(167, 139, 250, .45);
            --text-hi: #ffffff;
            --text-mid: #94a3b8;
            --text-low: #64748b;
            --btn-bg: #ffffff;
            --btn-text: #0b0b12;
            --grid-line: rgba(255, 255, 255, .045);
            --accent: #a78bfa;
            --accent-soft: rgba(139, 92, 246, .12);
            --logo-invert: 1;
        }

        html.light {
            --bg: #fbfbfd;
            --bg-alt: rgba(15, 23, 42, .025);
            --card: #ffffff;
            --card-hover: #ffffff;
            --border: rgba(15, 23, 42, .1);
            --border-strong: rgba(124, 58, 237, .45);
            --text-hi: #0f172a;
            --text-mid: #475569;
            --text-low: #64748b;
            --btn-bg: #0f172a;
            --btn-text: #ffffff;
            --grid-line: rgba(15, 23, 42, .05);
            --accent: #7c3aed;
            --accent-soft: rgba(124, 58, 237, .08);
            --logo-invert: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-mid);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
        }

        .t-hi { color: var(--text-hi); }
        .t-mid { color: var(--text-mid); }
        .t-low { color: var(--text-low); }
        .hover-hi:hover { color: var(--text-hi); }
        .accent { color: var(--accent); }
        .bg-alt { background: var(--bg-alt); }
        .border-base { border-color: var(--border); }
        .section-line { border-top: 1px solid var(--border); }

        .gradient-text {
            background: linear-gradient(120deg, #8b5cf6 0%, #6366f1 50%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        html.dark .gradient-text {
            background: linear-gradient(120deg, #c4b5fd 0%, #818cf8 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .btn-solid { background: var(--btn-bg); color: var(--btn-text); }
        .btn-solid:hover { opacity: .88; }

        .btn-ghost { border: 1px solid var(--border); color: var(--text-hi); }
        .btn-ghost:hover { background: var(--card-hover); }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
        }

        html.dark .card { backdrop-filter: blur(12px); }

        .card:hover {
            border-color: var(--border-strong);
            background: var(--card-hover);
        }

        html.light .card { box-shadow: 0 1px 2px rgba(15, 23, 42, .04); }
        html.light .card:hover { box-shadow: 0 12px 32px rgba(15, 23, 42, .08); }

        .pill {
            background: var(--accent-soft);
            border: 1px solid var(--border);
            color: var(--accent);
        }

        .aurora::before,
        .aurora::after {
            content: '';
            position: absolute;
            border-radius: 9999px;
            filter: blur(90px);
            pointer-events: none;
            opacity: .5;
        }

        html.light .aurora::before,
        html.light .aurora::after { opacity: .22; }

        .aurora::before {
            width: 34rem; height: 34rem;
            background: radial-gradient(circle, #7c3aed 0%, transparent 70%);
            top: -8rem; left: -6rem;
        }

        .aurora::after {
            width: 30rem; height: 30rem;
            background: radial-gradient(circle, #0ea5e9 0%, transparent 70%);
            bottom: -10rem; right: -8rem;
        }

        .grid-bg {
            background-image:
                linear-gradient(to right, var(--grid-line) 1px, transparent 1px),
                linear-gradient(to bottom, var(--grid-line) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 40%, transparent 100%);
        }

        .nav-scrolled {
            background: color-mix(in srgb, var(--bg) 82%, transparent);
            backdrop-filter: blur(16px);
        }

        .prose-plain { line-height: 1.75; white-space: pre-line; }

        html { scroll-behavior: smooth; }
        ::selection { background: var(--accent); color: #fff; }
    </style>

    @stack('styles')
</head>

<body class="antialiased">
    @php
        $siteOwner = \App\Models\User::where('role', 'admin')->first();
    @endphp

    <!-- Navigation -->
    <nav id="nav" class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b border-base">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="text-lg font-heading font-bold t-hi tracking-tight">
                    {{ $siteOwner->name ?? config('app.name', 'Portfolio') }}<span class="accent">.</span>
                </a>

                <div class="flex items-center gap-1">
                    <a href="{{ url('/') }}" class="hidden sm:inline-block px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">Home</a>
                    <a href="{{ route('courses.index') }}" class="hidden sm:inline-block px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">Courses</a>
                    <a href="{{ route('jobs.index') }}" class="hidden sm:inline-block px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">Work With Me</a>

                    <button type="button" id="theme-toggle" aria-label="Toggle dark mode"
                            class="ml-1 w-9 h-9 flex items-center justify-center rounded-full border border-base t-mid hover-hi transition-colors">
                        <i class="fas fa-moon text-sm" data-theme-icon></i>
                    </button>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="ml-1 px-3 py-2 text-sm font-medium t-mid hover-hi transition-colors">
                            <i class="fas fa-user-shield"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="section-line py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm t-low">
            <p>&copy; {{ date('Y') }} {{ $siteOwner->name ?? config('app.name', 'Portfolio') }}. All rights reserved.</p>
            <a href="#" class="hover:underline">Back to top ↑</a>
        </div>
    </footer>

    <script>
        const root = document.documentElement;
        const themeIcons = document.querySelectorAll('[data-theme-icon]');

        function paintThemeIcon() {
            const isDark = root.classList.contains('dark');
            themeIcons.forEach(i => i.className = (isDark ? 'fas fa-sun' : 'fas fa-moon') + ' text-sm');
        }

        document.getElementById('theme-toggle').addEventListener('click', function () {
            const isDark = root.classList.contains('dark');
            root.classList.remove(isDark ? 'dark' : 'light');
            root.classList.add(isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            paintThemeIcon();
        });

        paintThemeIcon();

        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => nav.classList.toggle('nav-scrolled', window.scrollY > 40), { passive: true });
    </script>

    @stack('scripts')
</body>
</html>
