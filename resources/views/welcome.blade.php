<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $teacher->name ?? config('app.name', 'Portfolio') }}</title>

    @if($teacher && $teacher->bio)
        <meta name="description" content="{{ Str::limit($teacher->bio, 160) }}">
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

        .btn-solid {
            background: var(--btn-bg);
            color: var(--btn-text);
        }

        .btn-solid:hover { opacity: .88; }

        .btn-ghost {
            border: 1px solid var(--border);
            color: var(--text-hi);
        }

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

        /* Layered background: aurora blobs over a faint grid */
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

        /* Simple-icons logos are single-colour black; flip them in dark mode */
        .logo-mono { filter: invert(var(--logo-invert)); }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
        }

        .reveal.is-visible { opacity: 1; transform: none; }

        html { scroll-behavior: smooth; }
        ::selection { background: var(--accent); color: #fff; }
    </style>
</head>

<body class="antialiased">
    @php
        $navLinks = array_filter([
            'projects' => $projects->count() ? 'Projects' : null,
            'courses' => $courses->count() ? 'Courses' : null,
            'about' => ($teacher && ($teacher->education->count() || $teacher->experiences->count() || $teacher->years_experience)) ? 'About' : null,
            'skills' => $featuredSkills->count() ? 'Skills' : null,
            'publications' => $latestPublications->count() ? 'Publications' : null,
            'blog' => $latestPosts->count() ? 'Writing' : null,
        ]);
    @endphp

    <!-- Navigation -->
    <nav id="nav" class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b border-base">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="text-lg font-heading font-bold t-hi tracking-tight">
                    {{ $teacher->name ?? config('app.name', 'Portfolio') }}<span class="accent">.</span>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    @foreach($navLinks as $anchor => $label)
                        <a href="#{{ $anchor }}" class="px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">{{ $label }}</a>
                    @endforeach
                    <a href="{{ route('jobs.index') }}" class="px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">Work With Me</a>

                    <button type="button" id="theme-toggle" aria-label="Toggle dark mode"
                            class="ml-1 w-9 h-9 flex items-center justify-center rounded-full border border-base t-mid hover-hi transition-colors">
                        <i class="fas fa-moon text-sm" data-theme-icon></i>
                    </button>

                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium t-mid hover-hi transition-colors">
                            <i class="fas fa-user-shield mr-1"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 text-sm font-medium t-low hover:text-red-500 transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="ml-2 px-4 py-2 text-sm font-semibold rounded-full btn-solid transition-opacity">Login</a>
                    @endauth
                </div>

                <div class="flex items-center gap-2 md:hidden">
                    <button type="button" id="theme-toggle-mobile" aria-label="Toggle dark mode"
                            class="w-9 h-9 flex items-center justify-center rounded-full border border-base t-mid">
                        <i class="fas fa-moon text-sm" data-theme-icon></i>
                    </button>
                    <button type="button" id="mobile-menu-btn" class="p-2 t-mid rounded-lg">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2">
                <div class="flex flex-col">
                    @foreach($navLinks as $anchor => $label)
                        <a href="#{{ $anchor }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">{{ $label }}</a>
                    @endforeach
                    <a href="{{ route('jobs.index') }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">Work With Me</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">Dashboard</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <header class="relative overflow-hidden aurora pt-36 pb-24 lg:pt-44 lg:pb-32">
        <div class="absolute inset-0 grid-bg"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold t-hi leading-[0.95] mb-6">
                {{ $teacher->name ?? config('app.name', 'Portfolio') }}
            </h1>

            @if($teacher && $teacher->title)
                <p class="text-xl md:text-2xl gradient-text font-semibold mb-8">{{ $teacher->title }}</p>
            @endif

            @if($teacher && $teacher->bio)
                <p class="text-base md:text-lg t-mid max-w-2xl mx-auto mb-10 leading-relaxed">{{ $teacher->bio }}</p>
            @endif

            <div class="flex flex-wrap justify-center gap-3 mb-12">
                @if($projects->count())
                    <a href="#projects" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-solid transition-all hover:scale-105">
                        View My Work <i class="fas fa-arrow-down ml-2 text-xs"></i>
                    </a>
                @endif
                <a href="#contact" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-ghost transition-all hover:scale-105">
                    Get in Touch <i class="fas fa-envelope ml-2 text-xs"></i>
                </a>
            </div>

            @if($teacher)
                <div class="flex justify-center gap-3">
                    @foreach([
                        ['url' => $teacher->github, 'icon' => 'fab fa-github'],
                        ['url' => $teacher->linkedin, 'icon' => 'fab fa-linkedin-in'],
                        ['url' => $teacher->twitter, 'icon' => 'fab fa-twitter'],
                        ['url' => $teacher->google_scholar, 'icon' => 'fas fa-graduation-cap'],
                    ] as $social)
                        @if($social['url'])
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                               class="w-11 h-11 flex items-center justify-center rounded-full card t-mid transition-all hover:scale-110">
                                <i class="{{ $social['icon'] }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </header>

    <!-- Projects: every project the admin published, directly under the hero -->
    @if($projects->count())
    <section id="projects" class="relative py-20 lg:py-28 section-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12 reveal">
                <div>
                    <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Portfolio</span>
                    <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Projects</h2>
                </div>
                <p class="text-sm t-low">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <article class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 reveal">
                        @if($project->images && count($project->images) > 0)
                            <div class="relative h-44 overflow-hidden bg-alt">
                                <img src="{{ asset($project->images[0]) }}" alt="{{ $project->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.style.display='none'">
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-lg font-bold t-hi mb-2">{{ $project->title }}</h3>

                            @if($project->description)
                                <p class="text-sm t-mid leading-relaxed mb-4">{{ Str::limit($project->description, 110) }}</p>
                            @endif

                            @if($project->skills && $project->skills->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @foreach($project->skills as $skill)
                                        <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            @elseif($project->technologies_used)
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @foreach(explode(',', $project->technologies_used) as $tech)
                                        <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ trim($tech) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center gap-4 text-sm">
                                @if($project->live_demo_url)
                                    <a href="{{ $project->live_demo_url }}" target="_blank" rel="noopener" class="font-semibold accent hover:underline">Live Demo →</a>
                                @endif
                                @if($project->source_code_url)
                                    <a href="{{ $project->source_code_url }}" target="_blank" rel="noopener" class="font-semibold t-low hover:underline">Code →</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Courses -->
    @if($courses->count())
    <section id="courses" class="relative py-20 lg:py-28 section-line bg-alt">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12 reveal">
                <div>
                    <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Teaching</span>
                    <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Courses</h2>
                </div>
                <a href="{{ route('courses.index') }}" class="text-sm font-semibold accent hover:underline">All courses →</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                    @php $target = $course->link ?: route('courses.show', $course); @endphp
                    <a href="{{ $target }}" @if($course->link) target="_blank" rel="noopener" @endif
                       class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 reveal block">
                        @if($course->image)
                            <div class="relative h-40 overflow-hidden bg-alt">
                                <img src="{{ $course->image_url }}" alt="{{ $course->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.style.display='none'">
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-lg font-bold t-hi mb-2">{{ $course->title }}</h3>
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
        </div>
    </section>
    @endif

    <!-- About: education + counts of what the admin has published -->
    @php
        // Every stat below is derived from records the admin created — nothing hard-coded.
        $stats = [];

        if ($teacher) {
            // Set by hand on the admin profile page — no guessing from date ranges.
            if ($teacher->years_experience) {
                $stats[] = ['value' => $teacher->years_experience . '+', 'label' => 'Years Experience'];
            }
            if ($projects->count()) {
                $stats[] = ['value' => $projects->count(), 'label' => 'Projects'];
            }
            if ($courses->count()) {
                $stats[] = ['value' => $courses->count(), 'label' => 'Courses'];
            }
            if ($latestPublications->count()) {
                $stats[] = ['value' => $latestPublications->count(), 'label' => 'Publications'];
            }
            if ($featuredSkills->count()) {
                $stats[] = ['value' => $featuredSkills->count(), 'label' => 'Skills'];
            }
        }
    @endphp

    @if($teacher && ($teacher->education->count() || $teacher->experiences->count() || count($stats)))
    <section id="about" class="relative py-20 lg:py-28 section-line">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 reveal">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">About</span>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Background</h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                @if($teacher->education->count())
                    <div class="reveal">
                        <h3 class="text-xl font-bold t-hi mb-5">Education</h3>
                        <div class="space-y-4">
                            @foreach($teacher->education as $education)
                                <div class="card rounded-2xl p-6 transition-all">
                                    <div class="flex items-start justify-between gap-4 mb-2">
                                        <h4 class="text-base font-bold t-hi">{{ $education->degree }}</h4>
                                        @if($education->start_date)
                                            <span class="text-xs t-low font-medium whitespace-nowrap">
                                                {{ $education->start_date->format('Y') }}
                                                @if($education->is_current)
                                                    — Present
                                                @elseif($education->end_date)
                                                    — {{ $education->end_date->format('Y') }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    @if($education->field_of_study)
                                        <p class="accent font-semibold text-sm mb-1">{{ $education->field_of_study }}</p>
                                    @endif
                                    @if($education->institution)
                                        <p class="t-mid text-sm mb-3">{{ $education->institution }}</p>
                                    @endif
                                    @if($education->description)
                                        <p class="t-low text-sm leading-relaxed">{{ $education->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($stats))
                    <div class="reveal">
                        <h3 class="text-xl font-bold t-hi mb-5">By the numbers</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($stats as $stat)
                                <div class="card rounded-2xl p-6 text-center transition-all">
                                    <div class="text-4xl font-extrabold gradient-text mb-1">{{ $stat['value'] }}</div>
                                    <div class="text-xs font-semibold uppercase tracking-wider t-low">{{ $stat['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    <!-- Experience -->
    @if($teacher && $teacher->experiences->count())
    <section id="experience" class="relative py-20 lg:py-28 section-line bg-alt">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 reveal">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Career</span>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Experience</h2>
            </div>

            <div class="space-y-6">
                @foreach($teacher->experiences as $experience)
                    <div class="relative pl-8 reveal">
                        <span class="absolute left-0 top-0 bottom-0 w-px bg-gradient-to-b from-violet-500 to-sky-500"></span>
                        <span class="absolute -left-[5px] top-7 w-[11px] h-[11px] rounded-full" style="background: var(--accent); box-shadow: 0 0 0 4px var(--bg);"></span>
                        <div class="card rounded-2xl p-6 transition-all">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                                <div>
                                    <h3 class="text-lg font-bold t-hi">{{ $experience->position }}</h3>
                                    @if($experience->company)
                                        <p class="accent font-semibold text-sm">{{ $experience->company }}</p>
                                    @endif
                                </div>
                                @if($experience->start_date)
                                    <div class="text-xs t-low font-medium">
                                        {{ $experience->start_date->format('M Y') }}
                                        @if($experience->is_current)
                                            — Present
                                        @elseif($experience->end_date)
                                            — {{ $experience->end_date->format('M Y') }}
                                        @endif
                                        @if($experience->location)
                                            <span class="mx-1">•</span>{{ $experience->location }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @if($experience->description)
                                <p class="t-mid text-sm leading-relaxed">{{ $experience->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Skills: name + logo, exactly as entered in the admin -->
    @if($featuredSkills->count())
    <section id="skills" class="relative py-20 lg:py-28 section-line">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 reveal">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Stack</span>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Skills</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($featuredSkills as $skill)
                    <div class="card rounded-2xl p-6 flex flex-col items-center text-center gap-3 transition-all reveal">
                        <div class="w-12 h-12 flex items-center justify-center">
                            @if($skill->logo)
                                <img src="{{ $skill->logo_url }}" alt="{{ $skill->name }}" loading="lazy" class="w-10 h-10 object-contain">
                            @elseif($skill->simple_icon)
                                <img src="https://cdn.jsdelivr.net/npm/simple-icons@latest/icons/{{ $skill->simple_icon }}.svg"
                                     alt="{{ $skill->name }}" loading="lazy" class="w-9 h-9 object-contain logo-mono">
                            @elseif($skill->icon)
                                <i class="{{ Str::startsWith($skill->icon, ['fa', 'bi']) ? $skill->icon : 'fas ' . $skill->icon }} text-2xl accent"></i>
                            @else
                                <i class="fas fa-bolt text-2xl accent"></i>
                            @endif
                        </div>
                        <span class="font-semibold t-hi text-sm">{{ $skill->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Publications -->
    @if($latestPublications->count())
    <section id="publications" class="relative py-20 lg:py-28 section-line bg-alt">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 reveal">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Research</span>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Publications</h2>
            </div>

            <div class="space-y-4">
                @foreach($latestPublications as $publication)
                    <div class="card rounded-2xl p-6 transition-all reveal">
                        <h3 class="text-base font-bold t-hi mb-2">{{ $publication->title }}</h3>
                        @if($publication->authors)
                            <p class="accent font-semibold text-sm mb-1">{{ $publication->authors }}</p>
                        @endif
                        @if($publication->journal || $publication->conference || $publication->year)
                            <p class="t-low text-sm mb-3">
                                {{ $publication->journal ?? $publication->conference }}
                                @if(($publication->journal || $publication->conference) && $publication->year) • @endif
                                {{ $publication->year }}
                            </p>
                        @endif
                        @if($publication->external_link)
                            <a href="{{ $publication->external_link }}" target="_blank" rel="noopener" class="inline-flex items-center text-sm font-semibold accent hover:underline">
                                Read More <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Blog -->
    @if($latestPosts->count())
    <section id="blog" class="relative py-20 lg:py-28 section-line">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 reveal">
                <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Writing</span>
                <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Latest Posts</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($latestPosts as $post)
                    <div class="card rounded-2xl p-6 transition-all reveal">
                        <h3 class="text-lg font-bold t-hi mb-3">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="t-mid text-sm leading-relaxed mb-5">{{ $post->excerpt }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs t-low font-medium">
                            <span>{{ $post->published_at?->format('M d, Y') }}</span>
                            @if($post->reading_time)
                                <span>{{ $post->reading_time }} min read</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Work With Me -->
    @if($recentJobs->count())
    <section class="relative py-20 lg:py-28 section-line bg-alt">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12 reveal">
                <div>
                    <span class="text-xs font-semibold tracking-[0.2em] uppercase accent">Opportunities</span>
                    <h2 class="mt-2 text-3xl md:text-5xl font-bold t-hi">Work With Me</h2>
                </div>
                <a href="{{ route('jobs.index') }}" class="text-sm font-semibold accent hover:underline">All opportunities →</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recentJobs as $job)
                    <article class="card group rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1.5 reveal">
                        @if($job->images && count($job->images) > 0)
                            <div class="relative h-40 overflow-hidden bg-alt">
                                <img src="{{ asset('storage/' . $job->images[0]) }}" alt="{{ $job->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @if($job->featured)
                                    <span class="absolute top-3 right-3 px-2 py-1 text-[10px] font-bold rounded-full btn-solid">Featured</span>
                                @endif
                            </div>
                        @elseif($job->featured)
                            <div class="px-6 pt-6">
                                <span class="inline-block px-3 py-1 text-[10px] font-bold rounded-full btn-solid">Featured</span>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-lg font-bold t-hi mb-3">{{ $job->title }}</h3>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($job->project_type)
                                    <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ ucfirst($job->project_type) }}</span>
                                @endif
                                @if($job->location_type)
                                    <span class="px-2.5 py-1 text-[11px] font-semibold rounded-full pill">{{ ucfirst(str_replace('-', ' ', $job->location_type)) }}</span>
                                @endif
                            </div>
                            @if($job->description)
                                <p class="text-sm t-mid leading-relaxed mb-5">{{ Str::limit($job->description, 110) }}</p>
                            @endif
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm font-semibold accent hover:underline">
                                View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Contact -->
    <section id="contact" class="relative overflow-hidden aurora py-24 lg:py-32 section-line">
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-6xl font-bold t-hi mb-5">Get in touch</h2>
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                @if($teacher && $teacher->email)
                    <a href="mailto:{{ $teacher->email }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-solid transition-all hover:scale-105">
                        <i class="fas fa-envelope mr-2"></i>{{ $teacher->email }}
                    </a>
                @endif
                @if($teacher && $teacher->linkedin)
                    <a href="{{ $teacher->linkedin }}" target="_blank" rel="noopener" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-ghost transition-all hover:scale-105">
                        <i class="fab fa-linkedin mr-2"></i>LinkedIn
                    </a>
                @endif
                @if($teacher && $teacher->github)
                    <a href="{{ $teacher->github }}" target="_blank" rel="noopener" class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-full btn-ghost transition-all hover:scale-105">
                        <i class="fab fa-github mr-2"></i>GitHub
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="section-line py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm t-low">
            <p>&copy; {{ date('Y') }} {{ $teacher->name ?? config('app.name', 'Portfolio') }}. All rights reserved.</p>
            <a href="#" class="hover:underline">Back to top ↑</a>
        </div>
    </footer>

    <!-- Scroll to top -->
    <button id="scrollTop" class="fixed bottom-6 right-6 z-40 w-11 h-11 hidden items-center justify-center rounded-full btn-solid shadow-lg transition-all hover:scale-110">
        <i class="fas fa-arrow-up text-sm"></i>
    </button>

    <script>
        // Theme switcher
        const root = document.documentElement;
        const themeIcons = document.querySelectorAll('[data-theme-icon]');

        function paintThemeIcon() {
            const isDark = root.classList.contains('dark');
            themeIcons.forEach(i => i.className = (isDark ? 'fas fa-sun' : 'fas fa-moon') + ' text-sm');
        }

        function toggleTheme() {
            const isDark = root.classList.contains('dark');
            root.classList.remove(isDark ? 'dark' : 'light');
            root.classList.add(isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            paintThemeIcon();
        }

        document.getElementById('theme-toggle').addEventListener('click', toggleTheme);
        document.getElementById('theme-toggle-mobile').addEventListener('click', toggleTheme);
        paintThemeIcon();

        // Nav background + scroll-to-top visibility
        const nav = document.getElementById('nav');
        const scrollTopBtn = document.getElementById('scrollTop');

        function onScroll() {
            nav.classList.toggle('nav-scrolled', window.scrollY > 40);
            scrollTopBtn.classList.toggle('hidden', window.scrollY < 400);
            scrollTopBtn.classList.toggle('flex', window.scrollY >= 400);
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // Mobile menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => mobileMenu.classList.add('hidden')));

        // Reveal on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    entry.target.style.transitionDelay = Math.min(i * 60, 240) + 'ms';
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -10% 0px' });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>
