<!-- Navigation -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ url('/') }}" class="text-xl font-heading font-bold text-gray-900 hover:text-purple-600 transition-colors">
                {{ config('app.name', 'Portfolio') }}
            </a>
            <div class="flex items-center space-x-6">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-purple-600 transition-colors font-medium {{ request()->is('/') ? 'text-purple-600' : '' }}">
                    <i class="fas fa-home mr-1"></i>Home
                </a>
                <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-purple-600 transition-colors font-medium {{ request()->is('jobs*') ? 'text-purple-600' : '' }}">
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
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl hover:scale-105 transform transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
