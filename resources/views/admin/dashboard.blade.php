@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Header with Gradient -->
<div class="mb-8">
    <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 font-heading">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-purple-100 text-lg">Here's what's happening with your portfolio today.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-4xl font-bold">{{ date('d') }}</div>
                    <div class="text-sm text-purple-100">{{ date('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Overview with Gradient Cards -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 font-heading">Portfolio Overview</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Courses Card -->
        <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-slate-700 hover:-translate-y-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 bg-clip-text text-transparent">{{ $stats['courses'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Courses</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 group-hover:translate-x-1 transition-transform">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Projects Card -->
        <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-slate-700 hover:-translate-y-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent">{{ $stats['projects'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Projects</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-700 group-hover:translate-x-1 transition-transform">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Publications Card -->
        <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-slate-700 hover:-translate-y-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-purple-700 bg-clip-text text-transparent">{{ $stats['publications'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Publications</p>
            </div>
            <a href="{{ route('admin.publications.index') }}" class="inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 group-hover:translate-x-1 transition-transform">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Blog Posts Card -->
        <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-slate-700 hover:-translate-y-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-orange-700 bg-clip-text text-transparent">{{ $stats['blog_posts'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Blog Posts</p>
            </div>
            <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 group-hover:translate-x-1 transition-transform">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Messages Card -->
        <div class="group bg-white dark:bg-slate-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-6 border border-gray-100 dark:border-slate-700 hover:-translate-y-2">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-rose-700 bg-clip-text text-transparent">{{ $stats['total_messages'] ?? 0 }}</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Messages</p>
            </div>
            <a href="{{ route('admin.contact.index') }}" class="inline-flex items-center text-sm font-medium text-pink-600 dark:text-pink-400 hover:text-pink-700 group-hover:translate-x-1 transition-transform">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 font-heading">Quick Actions</h2>
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.courses.create') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Add Course</span>
            </a>

            <a href="{{ route('admin.projects.create') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Add Project</span>
            </a>

            <a href="{{ route('admin.blog.create') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Write Post</span>
            </a>

            <a href="{{ route('admin.publications.create') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Add Publication</span>
            </a>

            <a href="{{ route('admin.tags.create') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-plus text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Add Tag</span>
            </a>

            <a href="{{ route('admin.profile.edit') }}" class="group flex flex-col items-center p-4 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all">
                <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-user-edit text-white text-lg"></i>
                </div>
                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 text-center">Edit Profile</span>
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity & Messages -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-heading">Recent Activity</h3>
            <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-purple-600 dark:text-purple-400"></i>
            </div>
        </div>
        @if($recentActivity && count($recentActivity) > 0)
            <div class="space-y-3">
                @foreach($recentActivity->take(5) as $activity)
                    <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-xl hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-gradient-to-br {{ $activity['type'] === 'course' ? 'from-blue-500 to-blue-600' : ($activity['type'] === 'project' ? 'from-green-500 to-emerald-600' : 'from-orange-500 to-orange-600') }} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                            <i class="fas fa-{{ $activity['type'] === 'course' ? 'book' : ($activity['type'] === 'project' ? 'code' : 'pencil-alt') }} text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $activity['action'] }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ $activity['title'] }}</p>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 flex-shrink-0">{{ $activity['date'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-3xl text-purple-400 dark:text-purple-600"></i>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400">No recent activity</p>
            </div>
        @endif
    </div>

    <!-- Messages -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white font-heading">Messages</h3>
            <div class="w-10 h-10 bg-pink-100 dark:bg-pink-900/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-envelope text-pink-600 dark:text-pink-400"></i>
            </div>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20 rounded-xl">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Unread Messages</span>
                <span class="px-3 py-1.5 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-bold rounded-lg shadow-lg">{{ $stats['unread_messages'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 rounded-xl">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Total Messages</span>
                <span class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-sm font-bold rounded-lg shadow-lg">{{ $stats['total_messages'] ?? 0 }}</span>
            </div>
            <a href="{{ route('admin.contact.index') }}" class="block w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all transform hover:scale-105 text-center shadow-lg">
                <i class="fas fa-inbox mr-2"></i>View All Messages
            </a>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
