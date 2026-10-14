{{--
    Shared public navbar (home page + layouts.public).
    Expects $brand. $navLinks (anchor => label) is optional: the home page passes
    the sections it actually renders; other pages compute the same list here.
--}}
@php
    if (! isset($navLinks)) {
        $owner = \App\Models\User::where('role', 'admin')->withCount(['education', 'experiences'])->first();

        $navLinks = array_filter([
            'projects' => \App\Models\Project::whereIn('status', ['active', 'featured'])->exists() ? 'Projects' : null,
            'courses' => \App\Models\Course::where('is_published', true)->exists() ? 'Courses' : null,
            'about' => ($owner && ($owner->education_count || $owner->experiences_count || $owner->years_experience)) ? 'About' : null,
            'skills' => \App\Models\Skill::where('is_featured', true)->exists() ? 'Skills' : null,
            'publications' => \App\Models\Publication::exists() ? 'Publications' : null,
            'blog' => \App\Models\BlogPost::published()->exists() ? 'Writing' : null,
        ]);
    }

    // Anchors only resolve on the home page; elsewhere point back to it.
    $anchorBase = request()->routeIs('home') ? '' : url('/');
@endphp

<nav id="nav" class="fixed top-0 inset-x-0 z-50 transition-all duration-300 border-b border-base">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ url('/') }}" class="text-lg font-heading font-bold t-hi tracking-tight">
                {{ $brand }}<span class="accent">.</span>
            </a>

            <div class="hidden md:flex items-center gap-1">
                @foreach($navLinks as $anchor => $label)
                    <a href="{{ $anchorBase }}#{{ $anchor }}" class="px-3 py-2 text-sm font-medium t-mid hover-hi rounded-lg transition-colors">{{ $label }}</a>
                @endforeach
                <a href="{{ route('jobs.index') }}" class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('jobs.*') ? 't-hi' : 't-mid hover-hi' }}">Work With Me</a>

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
                        <button type="submit" aria-label="Logout" class="px-3 py-2 text-sm font-medium t-low hover:text-red-500 transition-colors">
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
                <button type="button" id="mobile-menu-btn" aria-label="Toggle menu" aria-expanded="false" class="p-2 t-mid rounded-lg">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden mobile-panel pb-4 pt-2">
            <div class="flex flex-col">
                @foreach($navLinks as $anchor => $label)
                    <a href="{{ $anchorBase }}#{{ $anchor }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">{{ $label }}</a>
                @endforeach
                <a href="{{ route('jobs.index') }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">Work With Me</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium t-mid rounded-lg">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
