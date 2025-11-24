<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $jobOffer->title }} - Work With Me</title>

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

        .sticky-sidebar {
            position: sticky;
            top: 100px;
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
    <section class="gradient-bg text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('jobs.index') }}"
                   class="inline-flex items-center px-2.5 py-1 text-xs bg-white/20 hover:bg-white/30 rounded-lg transition-all text-white font-medium">
                    <i class="fas fa-arrow-left mr-1.5"></i>Back to All Opportunities
                </a>
            </div>

            <!-- Featured Badge -->
            @if($jobOffer->featured)
                <div class="mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 bg-yellow-400 text-gray-900 rounded-full text-xs font-bold shadow-md">
                        <i class="fas fa-star mr-1"></i>Featured
                    </span>
                </div>
            @endif

            <!-- Job Title -->
            <h1 class="text-xl md:text-2xl lg:text-3xl font-heading font-bold mb-3">
                {{ $jobOffer->title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap gap-1.5 mb-2">
                <span class="inline-flex items-center px-2.5 py-0.5 text-xs bg-white/20 rounded-full text-white font-medium">
                    <i class="fas fa-briefcase mr-1"></i>{{ ucfirst($jobOffer->project_type) }}
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 text-xs bg-white/20 rounded-full text-white font-medium">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}
                </span>
                @if($jobOffer->duration)
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs bg-white/20 rounded-full text-white font-medium">
                        <i class="fas fa-clock mr-1"></i>{{ $jobOffer->duration }}
                    </span>
                @endif
                @if($jobOffer->budget_min || $jobOffer->budget_max)
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs bg-white/20 rounded-full text-white font-medium">
                        <i class="fas fa-dollar-sign mr-1"></i>{{ $jobOffer->budget_range }}
                    </span>
                @endif
            </div>

            <!-- Posted Date -->
            <p class="text-white/80 flex items-center text-xs">
                <i class="fas fa-calendar mr-1.5"></i>Posted {{ $jobOffer->published_at->diffForHumans() }}
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Job Details -->
                <div class="lg:col-span-2 space-y-5">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h2 class="text-lg font-heading font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>About This Opportunity
                        </h2>
                        <div class="text-sm text-gray-700 leading-relaxed">
                            {!! nl2br(e($jobOffer->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($jobOffer->requirements)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <h2 class="text-lg font-heading font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>Requirements
                            </h2>
                            <div class="text-sm text-gray-700 leading-relaxed">
                                {!! nl2br(e($jobOffer->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Required Skills -->
                    @if($jobOffer->skills && $jobOffer->skills->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                            <h2 class="text-lg font-heading font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-code text-blue-600 mr-2"></i>Required Skills
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($jobOffer->skills as $skill)
                                    <span class="inline-flex items-center px-3 py-1.5 text-sm bg-gradient-to-r from-purple-100 to-blue-100 text-purple-700 rounded-lg font-medium hover:from-purple-200 hover:to-blue-200 transition-all">
                                        @if($skill->icon)
                                            <i class="fas {{ $skill->icon }} mr-1.5 text-xs"></i>
                                        @endif
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Application Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="gradient-bg px-5 py-4">
                            <h2 class="text-lg font-heading font-bold text-white flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>Apply for This Position
                            </h2>
                        </div>
                        <div class="p-5">
                            <form action="{{ route('jobs.apply', $jobOffer) }}" method="POST" enctype="multipart/form-data" id="applicationForm">
                                @csrf

                                <!-- Full Name -->
                                <div class="mb-4">
                                    <label for="full_name" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('full_name') border-red-500 @enderror"
                                           id="full_name"
                                           name="full_name"
                                           value="{{ old('full_name') }}"
                                           placeholder="Enter your full name"
                                           required>
                                    @error('full_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('email') border-red-500 @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="your.email@example.com"
                                           required>
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('phone') border-red-500 @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="+1 (555) 123-4567"
                                           required>
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- CV Upload -->
                                <div class="mb-4">
                                    <label for="cv_file" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        CV/Resume <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('cv_file') border-red-500 @enderror"
                                           id="cv_file"
                                           name="cv_file"
                                           accept=".pdf,.doc,.docx"
                                           required>
                                    <p class="mt-1.5 text-xs text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>PDF, DOC, DOCX (Max 5MB)
                                    </p>
                                    @error('cv_file')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cover Letter -->
                                <div class="mb-5">
                                    <label for="cover_letter" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Cover Letter <span class="text-gray-400 font-normal">(Optional)</span>
                                    </label>
                                    <textarea class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all @error('cover_letter') border-red-500 @enderror"
                                              id="cover_letter"
                                              name="cover_letter"
                                              rows="4"
                                              placeholder="Tell us why you're interested in this opportunity...">{{ old('cover_letter') }}</textarea>
                                    @error('cover_letter')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit"
                                        class="w-full px-6 py-2.5 text-sm bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all shadow-sm font-semibold">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit Application
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-5">
                    <!-- Quick Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky-sidebar">
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-base font-heading font-bold text-gray-900">Quick Information</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="pb-3 border-b border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Project Type</div>
                                <div class="font-semibold text-sm text-gray-900">{{ ucfirst($jobOffer->project_type) }}</div>
                            </div>

                            <div class="pb-3 border-b border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Location Type</div>
                                <div class="font-semibold text-sm text-gray-900">{{ ucfirst(str_replace('-', ' ', $jobOffer->location_type)) }}</div>
                            </div>

                            @if($jobOffer->location)
                                <div class="pb-3 border-b border-gray-100">
                                    <div class="text-xs text-gray-500 mb-1">Location</div>
                                    <div class="font-semibold text-sm text-gray-900">{{ $jobOffer->location }}</div>
                                </div>
                            @endif

                            @if($jobOffer->duration)
                                <div class="pb-3 border-b border-gray-100">
                                    <div class="text-xs text-gray-500 mb-1">Duration</div>
                                    <div class="font-semibold text-sm text-gray-900">{{ $jobOffer->duration }}</div>
                                </div>
                            @endif

                            @if($jobOffer->budget_min || $jobOffer->budget_max)
                                <div class="pb-3 border-b border-gray-100">
                                    <div class="text-xs text-gray-500 mb-1">Budget Range</div>
                                    <div class="font-bold text-purple-600 text-base">{{ $jobOffer->budget_range }}</div>
                                </div>
                            @endif

                            <div>
                                <div class="text-xs text-gray-500 mb-1">Posted</div>
                                <div class="font-semibold text-sm text-gray-900">{{ $jobOffer->published_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Jobs -->
                    @if($relatedJobs && $relatedJobs->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="text-base font-heading font-bold text-gray-900">Related Opportunities</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($relatedJobs as $related)
                                    <a href="{{ route('jobs.show', $related) }}"
                                       class="block p-3 hover:bg-purple-50 transition-colors group">
                                        <h4 class="font-semibold text-sm text-gray-900 mb-1.5 group-hover:text-purple-600 transition-colors leading-tight">
                                            {{ $related->title }}
                                        </h4>
                                        <div class="flex flex-wrap gap-1.5 text-xs">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded">
                                                {{ ucfirst($related->project_type) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 bg-green-100 text-green-700 rounded">
                                                {{ ucfirst(str_replace('-', ' ', $related->location_type)) }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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

    <script>
        // File upload validation
        document.getElementById('cv_file').addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB

            if (file && file.size > maxSize) {
                alert('File size must not exceed 5MB. Please choose a smaller file.');
                this.value = '';
            }
        });

        // Form submission confirmation
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const confirmed = confirm('Are you sure you want to submit this application? Please review your information before proceeding.');
            if (!confirmed) {
                e.preventDefault();
            }
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
