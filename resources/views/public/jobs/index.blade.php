<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Work With Me - Consulting Opportunities</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                        'heading': ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        'purple': {
                            600: '#8b5cf6',
                            700: '#7c3aed',
                        },
                        'blue': {
                            600: '#3b82f6',
                            700: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="text-xl font-heading font-bold text-gray-900 hover:text-purple-600 transition-colors">
                    {{ config('app.name', 'Portfolio') }}
                </a>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ url('/#about') }}" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">About</a>
                    <a href="{{ url('/#experience') }}" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Experience</a>
                    <a href="{{ url('/#skills') }}" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Skills</a>
                    <a href="{{ url('/#projects') }}" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Projects</a>
                    <a href="{{ url('/#publications') }}" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Publications</a>
                    <a href="{{ route('jobs.index') }}" class="text-purple-600 font-medium">
                        <i class="fas fa-briefcase mr-1"></i>Work With Me
                    </a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 transition-colors font-medium">
                            <i class="fas fa-user-shield mr-1"></i>Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors font-medium">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:scale-105 transform transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-btn"
                        class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 p-2 rounded-md">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 pt-2">
                <div class="flex flex-col space-y-2">
                    <a href="{{ url('/#about') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                    <a href="{{ url('/#experience') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Experience</a>
                    <a href="{{ url('/#skills') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Skills</a>
                    <a href="{{ url('/#projects') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                    <a href="{{ url('/#publications') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Publications</a>
                    <a href="{{ route('jobs.index') }}" class="text-purple-600 px-3 py-2 rounded-md text-sm font-medium"><i class="fas fa-briefcase mr-1"></i>Work With Me</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"><i class="fas fa-user-shield mr-1"></i>Dashboard</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-10 h-10 bg-white/20 rounded-lg mb-3">
                    <i class="fas fa-briefcase text-lg"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-heading font-bold mb-2">
                    Work With Me
                </h1>
                <p class="text-xs md:text-sm text-white/90 leading-relaxed">
                    Explore current consulting and freelance opportunities. I'm always open to exciting projects
                    and collaborations that challenge and inspire innovation.
                </p>
            </div>
        </div>
    </section>

    <!-- Search and Filters -->
    <section class="py-6 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('jobs.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search -->
                <div class="md:col-span-4">
                    <label for="search" class="block text-xs font-medium text-gray-700 mb-1.5">Search Opportunities</label>
                    <input type="text"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search titles, descriptions...">
                </div>

                <!-- Project Type Filter -->
                <div class="md:col-span-3">
                    <label for="type" class="block text-xs font-medium text-gray-700 mb-1.5">Project Type</label>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            id="type"
                            name="type">
                        <option value="">All Types</option>
                        @foreach($projectTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Location Type Filter -->
                <div class="md:col-span-3">
                    <label for="location" class="block text-xs font-medium text-gray-700 mb-1.5">Location</label>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all"
                            id="location"
                            name="location">
                        <option value="">All Locations</option>
                        @foreach($locationTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('location') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2 text-sm bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all font-medium shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('jobs.index') }}"
                       class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Job Offers Grid -->
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($jobOffers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($jobOffers as $job)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-lg card-hover border border-gray-200 overflow-hidden group">
                            <!-- Card Header with Featured Badge -->
                            @if($job->featured)
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-3 py-1.5">
                                    <span class="text-white text-xs font-semibold flex items-center">
                                        <i class="fas fa-star mr-1.5 text-xs"></i>Featured
                                    </span>
                                </div>
                            @endif

                            <div class="p-4">
                                <!-- Job Title -->
                                <h3 class="text-lg font-heading font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors leading-tight">
                                    {{ $job->title }}
                                </h3>

                                <!-- Job Meta Info -->
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-briefcase mr-1 text-xs"></i>{{ ucfirst($job->project_type) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-map-marker-alt mr-1 text-xs"></i>{{ ucfirst(str_replace('-', ' ', $job->location_type)) }}
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-3 leading-relaxed line-clamp-2">
                                    {{ Str::limit($job->description, 120) }}
                                </p>

                                <!-- Skills -->
                                @if($job->skills && $job->skills->count() > 0)
                                    <div class="mb-3">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($job->skills->take(3) as $skill)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-medium hover:bg-purple-100 hover:text-purple-700 transition-colors">
                                                    {{ $skill->name }}
                                                </span>
                                            @endforeach
                                            @if($job->skills->count() > 3)
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-xs font-medium">
                                                    +{{ $job->skills->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Budget -->
                                @if($job->budget_min || $job->budget_max)
                                    <div class="mb-3">
                                        <span class="text-purple-600 font-bold text-base">
                                            <i class="fas fa-dollar-sign mr-1 text-sm"></i>{{ $job->budget_range }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Duration -->
                                @if($job->duration)
                                    <div class="mb-3 text-gray-600 text-xs flex items-center">
                                        <i class="fas fa-clock mr-1.5 text-gray-400"></i>
                                        <span>{{ $job->duration }}</span>
                                    </div>
                                @endif

                                <!-- Action Button -->
                                <a href="{{ route('jobs.show', $job) }}"
                                   class="block w-full px-4 py-2 text-sm bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all text-center font-medium shadow-sm">
                                    View Details & Apply
                                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                                </a>

                                <!-- Posted Date -->
                                <div class="text-gray-500 text-xs mt-3 text-center">
                                    Posted {{ $job->published_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center">
                    {{ $jobOffers->withQueryString()->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="max-w-xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-full mb-4">
                            <i class="fas fa-info-circle text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-gray-900 mb-3">No Opportunities Found</h3>
                        <p class="text-gray-600 mb-5 text-sm">
                            @if(request()->hasAny(['search', 'type', 'location']))
                                Try adjusting your filters or
                                <a href="{{ route('jobs.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold underline">clear all filters</a>.
                            @else
                                There are no active job offers at the moment. Please check back later!
                            @endif
                        </p>
                        <a href="{{ url('/') }}"
                           class="inline-flex items-center px-5 py-2 text-sm bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all font-medium shadow-sm">
                            <i class="fas fa-home mr-2"></i>Back to Home
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} {{ config('app.name', 'Portfolio') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
