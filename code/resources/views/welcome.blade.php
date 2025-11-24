<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $teacher->name ?? 'Professional Portfolio' }}</title>

    <!-- Meta Description -->
    <meta name="description" content="{{ Str::limit($teacher->bio ?? 'Professional portfolio', 160) }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                        'heading': ['Space Grotesk', 'sans-serif'],
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
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="text-xl font-heading font-bold text-gray-900 hover:text-purple-600 transition-colors">
                    {{ config('app.name', 'Portfolio') }}
                </a>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="#about" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">About</a>
                    <a href="#experience" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Experience</a>
                    <a href="#skills" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Skills</a>
                    <a href="#projects" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Projects</a>
                    <a href="#publications" class="text-gray-600 hover:text-purple-600 transition-colors text-sm font-medium">Publications</a>
                    <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-purple-600 transition-colors font-medium">
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
                    <a href="#about" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                    <a href="#experience" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Experience</a>
                    <a href="#skills" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Skills</a>
                    <a href="#projects" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                    <a href="#publications" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">Publications</a>
                    <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"><i class="fas fa-briefcase mr-1"></i>Work With Me</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium"><i class="fas fa-user-shield mr-1"></i>Dashboard</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-br from-purple-50 to-blue-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center fade-in-up">
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 mb-6 leading-tight font-heading">
                    {{ $teacher->name ?? 'Professional Developer' }}
                </h1>
                <h2 class="text-xl md:text-2xl text-slate-600 mb-8 font-medium">
                    {{ $teacher->title ?? 'Full-Stack Developer & Researcher' }}
                </h2>
                @if($teacher && $teacher->bio)
                    <p class="text-lg text-slate-700 max-w-3xl mx-auto mb-10 leading-relaxed">
                        {{ $teacher->bio }}
                    </p>
                @endif

                <!-- Social Links -->
                @if($teacher)
                    <div class="flex justify-center space-x-4 mb-10">
                        @if($teacher->github)
                            <a href="{{ $teacher->github }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 text-slate-700 hover:bg-slate-900 hover:text-white transition-all transform hover:scale-110">
                                <i class="fab fa-github text-lg"></i>
                            </a>
                        @endif
                        @if($teacher->linkedin)
                            <a href="{{ $teacher->linkedin }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 text-slate-700 hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110">
                                <i class="fab fa-linkedin-in text-lg"></i>
                            </a>
                        @endif
                        @if($teacher->twitter)
                            <a href="{{ $teacher->twitter }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 text-slate-700 hover:bg-blue-400 hover:text-white transition-all transform hover:scale-110">
                                <i class="fab fa-twitter text-lg"></i>
                            </a>
                        @endif
                        @if($teacher->google_scholar)
                            <a href="{{ $teacher->google_scholar }}" target="_blank" class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-200 text-slate-700 hover:bg-red-600 hover:text-white transition-all transform hover:scale-110">
                                <i class="fas fa-graduation-cap text-lg"></i>
                            </a>
                        @endif
                    </div>
                @endif

                <div class="flex justify-center space-x-4">
                    <a href="#projects" class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
                        View My Work
                        <i class="fas fa-arrow-down ml-2"></i>
                    </a>
                    <a href="#contact" class="inline-flex items-center px-8 py-3 border-2 border-purple-600 text-sm font-medium rounded-lg text-purple-600 bg-white hover:bg-purple-600 hover:text-white transition-all transform hover:scale-105">
                        Get in Touch
                        <i class="fas fa-envelope ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Me Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">About Me</h2>
                <p class="text-xl text-slate-600">Combining technical expertise with research excellence</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Education -->
                @if($teacher && $teacher->education && $teacher->education->count() > 0)
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-6 font-heading">Education</h3>
                        <div class="space-y-6">
                            @foreach($teacher->education->take(2) as $education)
                                <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-purple-300 hover:shadow-lg transition-all">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-lg font-bold text-slate-900 font-heading">{{ $education->degree }}</h4>
                                        <span class="text-sm text-slate-500 font-medium">{{ $education->start_date->format('Y') }} - {{ $education->is_current ? 'Present' : $education->end_date->format('Y') }}</span>
                                    </div>
                                    <p class="text-purple-600 font-semibold mb-1">{{ $education->field_of_study }}</p>
                                    <p class="text-slate-600 mb-3">{{ $education->institution }}</p>
                                    @if($education->description)
                                        <p class="text-slate-700 text-sm leading-relaxed">{{ $education->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quick Stats -->
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-6 font-heading">Quick Stats</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Years Experience -->
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 text-center border border-purple-100 hover:shadow-lg transition-all">
                            <div class="text-4xl font-extrabold gradient-text mb-2">
                                @if($teacher && $teacher->experiences->count() > 0)
                                    @php
                                        $totalYears = 0;
                                        foreach($teacher->experiences as $exp) {
                                            $endDate = $exp->is_current ? now() : $exp->end_date;
                                            $totalYears += $exp->start_date->diffInYears($endDate);
                                        }
                                        echo $totalYears;
                                    @endphp+
                                @else
                                    5+
                                @endif
                            </div>
                            <div class="text-slate-600 text-sm font-semibold">Years Experience</div>
                        </div>

                        <!-- Projects -->
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 text-center border border-purple-100 hover:shadow-lg transition-all">
                            <div class="text-4xl font-extrabold gradient-text mb-2">{{ $projects->count() }}</div>
                            <div class="text-slate-600 text-sm font-semibold">Featured Projects</div>
                        </div>

                        <!-- Publications -->
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 text-center border border-purple-100 hover:shadow-lg transition-all">
                            <div class="text-4xl font-extrabold gradient-text mb-2">{{ $latestPublications->count() }}</div>
                            <div class="text-slate-600 text-sm font-semibold">Publications</div>
                        </div>

                        <!-- Skills -->
                        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 text-center border border-purple-100 hover:shadow-lg transition-all">
                            <div class="text-4xl font-extrabold gradient-text mb-2">{{ $featuredSkills->count() }}</div>
                            <div class="text-slate-600 text-sm font-semibold">Core Skills</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Professional Experience -->
    @if($teacher && $teacher->experiences && $teacher->experiences->count() > 0)
    <section id="experience" class="py-20 bg-gradient-to-br from-slate-50 to-purple-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Professional Experience</h2>
                <p class="text-xl text-slate-600">My journey in software development and research</p>
            </div>

            <div class="space-y-8">
                @foreach($teacher->experiences->take(3) as $index => $experience)
                    <div class="relative pl-8 before:content-[''] before:absolute before:left-0 before:top-0 before:bottom-0 before:w-0.5 before:bg-gradient-to-b before:from-purple-600 before:to-blue-600">
                        <div class="absolute left-0 top-2 w-4 h-4 bg-purple-600 rounded-full border-4 border-white shadow-lg"></div>
                        <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 hover:border-purple-300 hover:shadow-xl transition-all">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 font-heading">{{ $experience->position }}</h3>
                                    <p class="text-purple-600 font-semibold">{{ $experience->company }}</p>
                                </div>
                                <div class="text-sm text-slate-500 font-medium mt-2 md:mt-0">
                                    {{ $experience->start_date->format('M Y') }} - {{ $experience->is_current ? 'Present' : $experience->end_date->format('M Y') }}
                                    @if($experience->location)
                                        <span class="mx-2">•</span>
                                        <span>{{ $experience->location }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($experience->description)
                                <p class="text-slate-700 leading-relaxed">{{ $experience->description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Skills & Technologies -->
    @if($featuredSkills && $featuredSkills->count() > 0)
    <section id="skills" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Skills & Technologies</h2>
                <p class="text-xl text-slate-600">Technologies I work with</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                @foreach($featuredSkills as $skill)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-purple-300 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                @if($skill->icon)
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas {{ $skill->icon }} text-purple-600"></i>
                                    </div>
                                @endif
                                <span class="font-bold text-slate-900 font-heading">{{ $skill->name }}</span>
                            </div>
                            <span class="text-sm text-purple-600 font-semibold">{{ $skill->proficiency_label }}</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-purple-600 to-blue-600 h-2.5 rounded-full transition-all" style="width: {{ ($skill->proficiency_level / 5) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Projects -->
    @if($projects && $projects->count() > 0)
    <section id="projects" class="py-20 bg-gradient-to-br from-slate-50 to-purple-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Featured Projects</h2>
                <p class="text-xl text-slate-600">Some of my recent work</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($projects->take(6) as $project)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-slate-200 hover:border-purple-300 hover:shadow-2xl transition-all transform hover:-translate-y-2">
                        @if($project->images && count($project->images) > 0)
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                <img src="{{ asset('storage/' . $project->images[0]) }}"
                                     alt="{{ $project->title }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22400%22 height=%22300%22/%3E%3Ctext fill=%22%239ca3af%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-purple-100 to-blue-100 flex items-center justify-center">
                                <i class="fas fa-project-diagram text-6xl text-purple-300"></i>
                            </div>
                        @endif

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-slate-900 mb-3 font-heading">{{ $project->title }}</h3>
                            <p class="text-slate-600 mb-4 text-sm leading-relaxed">{{ Str::limit($project->description, 100) }}</p>

                            @if($project->skills && $project->skills->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($project->skills->take(3) as $skill)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            @elseif($project->technologies_used)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice(explode(',', $project->technologies_used), 0, 3) as $tech)
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">{{ trim($tech) }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center space-x-4 text-sm">
                                @if($project->live_demo_url)
                                    <a href="{{ $project->live_demo_url }}" target="_blank" class="text-purple-600 hover:text-purple-700 font-semibold transition-colors">
                                        Live Demo →
                                    </a>
                                @endif
                                @if($project->source_code_url)
                                    <a href="{{ $project->source_code_url }}" target="_blank" class="text-purple-600 hover:text-purple-700 font-semibold transition-colors">
                                        Code →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Recent Publications -->
    @if($latestPublications && $latestPublications->count() > 0)
    <section id="publications" class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Recent Publications</h2>
                <p class="text-xl text-slate-600">My research contributions</p>
            </div>

            <div class="space-y-6">
                @foreach($latestPublications as $publication)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-purple-300 hover:shadow-lg transition-all">
                        <h3 class="text-lg font-bold text-slate-900 mb-2 font-heading">{{ $publication->title }}</h3>
                        <p class="text-purple-600 font-semibold mb-2 text-sm">{{ $publication->authors }}</p>
                        <p class="text-slate-600 mb-3 text-sm">{{ $publication->journal ?? $publication->conference }} • {{ $publication->year }}</p>
                        @if($publication->external_link)
                            <a href="{{ $publication->external_link }}" target="_blank" class="text-purple-600 hover:text-purple-700 text-sm font-semibold transition-colors inline-flex items-center">
                                Read More
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Blog Posts -->
    @if($latestPosts && $latestPosts->count() > 0)
    <section id="blog" class="py-20 bg-gradient-to-br from-slate-50 to-purple-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Latest Blog Posts</h2>
                <p class="text-xl text-slate-600">Thoughts and insights</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                @foreach($latestPosts as $post)
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 hover:border-purple-300 hover:shadow-xl transition-all">
                        <h3 class="text-xl font-bold text-slate-900 mb-3 font-heading">{{ $post->title }}</h3>
                        <p class="text-slate-600 mb-4 leading-relaxed">{{ $post->excerpt }}</p>
                        <div class="flex items-center justify-between text-sm text-slate-500">
                            <span class="font-medium">{{ $post->published_at->format('M d, Y') }}</span>
                            <span class="font-medium">{{ $post->reading_time }} min read</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Work With Me Section -->
    @if($recentJobs && $recentJobs->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4 font-heading">Work With Me</h2>
                <p class="text-xl text-slate-600">Current consulting and freelance opportunities</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentJobs->take(3) as $job)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-purple-300 hover:shadow-lg transition-all">
                        @if($job->featured)
                            <span class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-4">
                                Featured
                            </span>
                        @endif

                        <h3 class="text-xl font-bold text-slate-900 mb-3 font-heading">{{ $job->title }}</h3>

                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ ucfirst($job->project_type) }}</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">{{ ucfirst(str_replace('-', ' ', $job->location_type)) }}</span>
                        </div>

                        <p class="text-slate-600 mb-4 text-sm leading-relaxed">{{ Str::limit($job->description, 120) }}</p>

                        <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold text-sm transition-colors">
                            View Details
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg">
                    View All Opportunities
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact CTA -->
    <section id="contact" class="py-20 bg-gradient-to-br from-purple-600 to-blue-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 font-heading">Let's Work Together</h2>
            <p class="text-xl text-purple-100 mb-10 leading-relaxed">
                Interested in collaboration or have a project in mind?
            </p>
            <div class="flex justify-center space-x-4">
                @if($teacher && $teacher->linkedin)
                    <a href="{{ $teacher->linkedin }}" target="_blank" class="inline-flex items-center px-8 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:bg-slate-100 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fab fa-linkedin mr-2"></i>
                        Connect on LinkedIn
                    </a>
                @endif
                @if($teacher && $teacher->github)
                    <a href="{{ $teacher->github }}" target="_blank" class="inline-flex items-center px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-all transform hover:scale-105">
                        <i class="fab fa-github mr-2"></i>
                        View GitHub
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-400">&copy; {{ date('Y') }} {{ $teacher->name ?? 'Portfolio' }}. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollTop" class="fixed bottom-8 right-8 bg-gradient-to-r from-purple-600 to-blue-600 text-white p-3 rounded-full shadow-lg hover:from-purple-700 hover:to-blue-700 transition-all transform hover:scale-110 hidden">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Scroll to top button
        const scrollTopBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.remove('hidden');
            } else {
                scrollTopBtn.classList.add('hidden');
            }
        });

        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

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
