<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Portfolio') }}</title>

    <!-- Meta Description -->
    <meta name="description" content="Sign in to access your professional portfolio">

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

<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-purple-50 h-screen flex items-center justify-center p-4 overflow-hidden">
    <!-- Login Container -->
    <div class="w-full max-w-md">
        <!-- Return to Home Button -->
        <div class="text-center mb-3">
            <a href="{{ url('/') }}"
               class="inline-flex items-center px-5 py-2 bg-white text-gray-700 rounded-xl hover:bg-gray-50 transition-all shadow-md hover:shadow-lg font-medium text-sm">
                <i class="fas fa-home mr-2"></i>
                Return to Main Page
            </a>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 backdrop-blur-sm border border-white/20">
            <!-- Logo/Icon -->
            <div class="text-center mb-5">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-2 shadow-lg">
                    <i class="fas fa-user-shield text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 font-heading">Welcome Back</h1>
                <p class="text-gray-600 text-sm mt-1">Sign in to access your account</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               autofocus
                               placeholder="Enter your email"
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-sm @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Enter your password"
                               class="block w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-sm @error('password') border-red-500 @enderror">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember"
                               name="remember"
                               type="checkbox"
                               {{ old('remember') ? 'checked' : '' }}
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-xs text-gray-700">
                            Remember me
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <div class="text-xs">
                            <a href="{{ route('password.request') }}" class="font-medium text-purple-600 hover:text-purple-500 transition-colors">
                                Forgot password?
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" id="loginBtn"
                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-105">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span id="loginBtnText">Sign In</span>
                </button>
            </form>

            <!-- Additional Info - Compact -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-center space-x-3 text-xs text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-shield-check text-green-500 mr-1 text-sm"></i>
                        <span>Secure</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-lock text-purple-500 mr-1 text-sm"></i>
                        <span>Encrypted</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Text - Compact -->
        <div class="text-center mt-3 text-xs text-gray-600">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Portfolio') }}</p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
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

        // Form submission loading state
        document.querySelector('form').addEventListener('submit', function() {
            const loginBtn = document.getElementById('loginBtn');
            const loginBtnText = document.getElementById('loginBtnText');

            loginBtn.disabled = true;
            loginBtn.classList.add('opacity-75', 'cursor-not-allowed');
            loginBtnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
        });
    </script>
</body>

</html>
