<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome - {{ $teacher->name ?? 'Professional Portfolio' }}</title>

    <!-- Meta Description -->
    <meta name="description" content="Sign in to access your professional portfolio and manage your content">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

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
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-bold text-slate-900">{{ $teacher->name ?? 'Portfolio' }}</h1>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#about"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="#experience"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Experience</a>
                        <a href="#skills"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Skills</a>
                        <a href="#projects"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                        <a href="#publications"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Publications</a>
                        <a href="#blog"
                            class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Blog</a>
                        @auth
                            <a href="{{ route('admin.dashboard') }}"
                                class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-purple-700 transition-colors">Login</a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-btn"
                        class="text-slate-600 hover:text-slate-900 hover:bg-slate-100 p-2 rounded-md">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-2">
                    <a href="#about" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">About</a>
                    <a href="#experience" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Experience</a>
                    <a href="#skills" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Skills</a>
                    <a href="#projects" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Projects</a>
                    <a href="#publications" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Publications</a>
                    <a href="#blog" class="text-slate-600 hover:text-slate-900 px-3 py-2 rounded-md text-sm font-medium">Blog</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 pb-12 bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Welcome Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Academic Portfolio
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-slate-900 mb-6 font-heading">
                        Welcome Back
                    </h1>
                    <p class="text-xl text-slate-600 mb-8 leading-relaxed">
                        Sign in to access your academic portfolio and manage your content with ease.
                    </p>

                    <!-- Feature highlights -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center space-x-3 p-4 bg-white/70 backdrop-blur-sm rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-check text-purple-600"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-slate-900">Secure Access</h3>
                                <p class="text-sm text-slate-600">Enterprise-level security</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 p-4 bg-white/70 backdrop-blur-sm rounded-lg">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-sync-alt text-blue-600"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-slate-900">Auto Sync</h3>
                                <p class="text-sm text-slate-600">Real-time updates</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="lg:max-w-md mx-auto w-full">
                    <div class="bg-white rounded-2xl shadow-xl p-8 backdrop-blur-sm border border-white/20">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 font-heading">Sign In</h2>
                            <p class="text-slate-600 mt-2">Enter your credentials to continue</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                    Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-slate-400"></i>
                                    </div>
                                    <input id="email"
                                           type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autocomplete="email"
                                           autofocus
                                           placeholder="Enter your email"
                                           class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-slate-400"></i>
                                    </div>
                                    <input id="password"
                                           type="password"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                           placeholder="Enter your password"
                                           class="block w-full pl-10 pr-12 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors @error('password') border-red-500 @enderror">
                                    <button type="button" id="togglePassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember"
                                           name="remember"
                                           type="checkbox"
                                           {{ old('remember') ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-slate-700">
                                        Remember me
                                    </label>
                                </div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-purple-600 hover:text-purple-500 transition-colors">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="loginBtn"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-105">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                <span id="loginBtnText">Sign In</span>
                            </button>
                        </form>

                        <!-- Back to Portfolio -->
                        <div class="mt-6 text-center">
                            <a href="{{ route('home') }}"
                               class="inline-flex items-center text-sm text-slate-600 hover:text-slate-900 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Portfolio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-900 font-heading mb-4">Manage Your Academic Portfolio</h2>
                <p class="text-xl text-slate-600">Everything you need to showcase your academic achievements</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book-open text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2 font-heading">Manage Publications</h3>
                    <p class="text-slate-600">Organize and showcase your research publications and academic work.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2 font-heading">Course Management</h3>
                    <p class="text-slate-600">Efficiently manage your courses, students, and teaching materials.</p>
                </div>

                <div class="text-center group">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2 font-heading">Track Progress</h3>
                    <p class="text-slate-600">Monitor your academic progress and research milestones.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} {{ $teacher->name ?? 'Portfolio' }}. All rights reserved.</p>
            @if($teacher && $teacher->specializations)
                <p class="text-slate-400 mt-2">{{ $teacher->specializations }}</p>
            @endif
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollTop"
        class="fixed bottom-8 right-8 bg-purple-600 text-white p-3 rounded-full shadow-lg hover:bg-purple-700 transition-colors hidden">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Form submission
        document.querySelector('form').addEventListener('submit', function() {
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnText = document.getElementById('loginBtnText');

            loginBtn.disabled = true;
            loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
            loginBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
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
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.pageYOffset > 50) {
                nav.classList.add('bg-white/90', 'backdrop-blur-sm');
            } else {
                nav.classList.remove('bg-white/90', 'backdrop-blur-sm');
            }
        });
    </script>
</body>

</html>