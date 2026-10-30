<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                    }
                }
            }
        }
    </script>

    <!-- Axios CDN -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-width-mobile: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar transitions */
        #sidebar {
            width: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        #content {
            margin-left: var(--sidebar-width);
            min-width: 0;
            transition: all 0.3s ease;
        }

        /* Hover effects for cards */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Mobile sidebar */
        @media (max-width: 1024px) {
            #sidebar {
                width: var(--sidebar-width-mobile);
                overflow: hidden;
            }

            #sidebar:hover, #sidebar.mobile-expanded {
                width: var(--sidebar-width);
                overflow: visible;
            }

            #content {
                margin-left: var(--sidebar-width-mobile);
            }

            #sidebar:hover ~ #content,
            #sidebar.mobile-expanded ~ #content {
                margin-left: var(--sidebar-width);
            }

            .menu-text {
                opacity: 0;
                transition: opacity 0.3s;
                white-space: nowrap;
            }

            #sidebar:hover .menu-text,
            #sidebar.mobile-expanded .menu-text {
                opacity: 1;
            }

            .sidebar-header h3 {
                opacity: 0;
                transition: opacity 0.3s;
            }

            #sidebar:hover .sidebar-header h3,
            #sidebar.mobile-expanded .sidebar-header h3 {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                width: var(--sidebar-width);
                overflow: hidden;
            }

            #sidebar .menu-text,
            #sidebar .sidebar-header h3 {
                opacity: 1;
            }

            #sidebar.mobile-expanded {
                transform: translateX(0);
            }

            #content {
                margin-left: 0;
            }

            #sidebar:hover ~ #content,
            #sidebar.mobile-expanded ~ #content {
                margin-left: 0;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            #sidebar.mobile-expanded ~ .overlay {
                display: block;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-50 overflow-x-hidden">
    <!-- Sidebar -->
    <nav id="sidebar" class="fixed top-0 left-0 h-full flex flex-col bg-slate-800 text-white shadow-xl z-30">
        <!-- Sidebar Header -->
        <div class="sidebar-header flex items-center px-6 py-5 border-b border-slate-700 shrink-0">
            <i class="fas fa-chalkboard-teacher text-2xl text-blue-400 mr-3"></i>
            <h3 class="text-lg font-semibold menu-text whitespace-nowrap truncate min-w-0">Teacher Dashboard</h3>
            <button type="button" id="sidebarClose" class="md:hidden ml-auto -mr-2 p-2 text-gray-400 hover:text-white" aria-label="Close menu">
                <i class="fas fa-xmark text-xl"></i>
            </button>
        </div>

        @php
            // Day-to-day pages stay visible; portfolio content is grouped below.
            $mainLinks = [
                ['admin.dashboard', 'admin.dashboard', 'fa-home', 'Dashboard'],
                ['admin.job-offers.index', 'admin.job-offers.*', 'fa-briefcase', 'Job Offers'],
                ['admin.job-applications.index', 'admin.job-applications.*', 'fa-paper-plane', 'Applications'],
                ['admin.contact.index', 'admin.contact.*', 'fa-envelope', 'Messages'],
                ['admin.projects.index', 'admin.projects.*', 'fa-project-diagram', 'Projects'],
                ['admin.blog.index', 'admin.blog.*', 'fa-blog', 'Blog Posts'],
            ];
            $contentLinks = [
                ['admin.courses.index', 'admin.courses.*', 'fa-book', 'Courses'],
                ['admin.publications.index', 'admin.publications.*', 'fa-file-alt', 'Publications'],
                ['admin.education.index', 'admin.education.*', 'fa-graduation-cap', 'Education'],
                ['admin.experiences.index', 'admin.experiences.*', 'fa-timeline', 'Experience'],
                ['admin.skills.index', 'admin.skills.*', 'fa-star', 'Skills'],
                ['admin.tags.index', 'admin.tags.*', 'fa-tags', 'Tags'],
            ];
            $contentOpen = collect($contentLinks)->contains(fn ($link) => request()->routeIs($link[1]));
            $unreadMessages = $stats['unread_messages'] ?? 0;
        @endphp

        <!-- Navigation Menu -->
        <div class="p-3 flex-1 overflow-y-auto overflow-x-hidden">
            <ul class="space-y-1">
                @foreach($mainLinks as [$route, $pattern, $icon, $label])
                    <li>
                        <a href="{{ route($route) }}" class="flex items-center px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 border-l-4 {{ request()->routeIs($pattern) ? 'border-blue-400 bg-slate-700 text-white' : 'border-transparent' }}">
                            <i class="fas {{ $icon }} w-6 text-center mr-3"></i>
                            <span class="menu-text">{{ $label }}</span>
                            @if($route === 'admin.contact.index' && $unreadMessages > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadMessages }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            <details class="group/content mt-3 pt-3 border-t border-slate-700" {{ $contentOpen ? 'open' : '' }}>
                <summary class="flex items-center px-3 py-2.5 rounded-lg text-gray-400 hover:bg-slate-700 hover:text-white cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                    <i class="fas fa-layer-group w-6 text-center mr-3 ml-1"></i>
                    <span class="menu-text text-sm font-medium">Portfolio content</span>
                    <i class="fas fa-chevron-down menu-text ml-auto text-xs transition-transform group-open/content:rotate-180"></i>
                </summary>
                <ul class="space-y-1 mt-1">
                    @foreach($contentLinks as [$route, $pattern, $icon, $label])
                        <li>
                            <a href="{{ route($route) }}" class="flex items-center px-3 py-2.5 rounded-lg text-gray-300 hover:bg-slate-700 hover:text-white transition-all duration-200 border-l-4 {{ request()->routeIs($pattern) ? 'border-blue-400 bg-slate-700 text-white' : 'border-transparent' }}">
                                <i class="fas {{ $icon }} w-6 text-center mr-3"></i>
                                <span class="menu-text">{{ $label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </div>

        <!-- Sidebar Footer -->
        <a href="{{ route('admin.profile.edit') }}" class="block w-full p-4 border-t border-slate-700 shrink-0 hover:bg-slate-700 transition-colors {{ request()->routeIs('admin.profile.*') ? 'bg-slate-700' : '' }}">
            <div class="flex items-center">
                <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80"
                     alt="User" class="w-10 h-10 rounded-full mr-3 shrink-0">
                <div class="menu-text min-w-0">
                    <h6 class="text-sm font-medium truncate">Dr. Sarah Johnson</h6>
                    <p class="text-xs text-gray-400">Profile settings</p>
                </div>
            </div>
        </a>
    </nav>

    <!-- Overlay for mobile -->
    <div class="overlay"></div>

    <!-- Main Content -->
    <div id="content" class="min-h-screen">
        <!-- Top Bar -->
        <div class="sticky top-0 z-20 bg-white shadow-sm border-b border-gray-200 px-4 py-3 sm:px-6 sm:py-4">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center min-w-0">
                    <button id="sidebarToggle" class="lg:hidden -ml-2 mr-2 p-2 text-gray-600 hover:text-gray-900" aria-label="Open menu">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-lg sm:text-2xl font-semibold text-gray-900 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-4 shrink-0">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative group" id="userMenu">
                        <button type="button" id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80"
                                 alt="User" class="w-8 h-8 rounded-full">
                            <span class="hidden md:block text-sm font-medium">Dr. Sarah Johnson</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-[.open]:opacity-100 group-[.open]:visible transition-all duration-200 z-10">
                            <div class="py-2">
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user w-4 mr-3"></i>
                                    Profile
                                </a>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog w-4 mr-3"></i>
                                    Dashboard
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-left">
                                        <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4 sm:p-6">
            @yield('content')
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global delete confirmation
            document.body.addEventListener('click', function(e) {
                const deleteButton = e.target.closest('[data-confirm-delete]');
                if (deleteButton) {
                    const message = deleteButton.getAttribute('data-confirm-message') || 'Are you sure you want to delete this item?';
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                }
            });

            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.querySelector('.overlay');

            function setSidebar(open) {
                sidebar.classList.toggle('mobile-expanded', open);
                // Stop the page scrolling behind the drawer on phones
                document.body.classList.toggle('overflow-hidden', open && window.innerWidth <= 768);

                // Keep the current page's link in view when the menu is long
                const activeLink = sidebar.querySelector('a.border-blue-400');
                if (open && activeLink) {
                    activeLink.scrollIntoView({ block: 'nearest' });
                }
            }

            // Toggle sidebar on mobile
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    setSidebar(!sidebar.classList.contains('mobile-expanded'));
                });
            }

            const sidebarClose = document.getElementById('sidebarClose');
            if (sidebarClose) {
                sidebarClose.addEventListener('click', function() {
                    setSidebar(false);
                });
            }

            // Close sidebar when clicking on overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    setSidebar(false);
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    setSidebar(false);
                }
            });

            // Close sidebar when clicking on menu items on mobile
            document.querySelectorAll('#sidebar a[href]').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setSidebar(false);
                    }
                });
            });

            // User menu: hover only works with a mouse, so also toggle on tap
            const userMenu = document.getElementById('userMenu');
            const userMenuButton = document.getElementById('userMenuButton');
            if (userMenu && userMenuButton) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('open');
                });
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target)) {
                        userMenu.classList.remove('open');
                    }
                });
            }

            // Auto-resize handling
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    setSidebar(false);
                }
            });
        });
    </script>
    <script>
        function previewImages(event) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = '<img src="' + e.target.result + '" class="rounded-lg border border-gray-200 w-full h-24 object-cover">';
                    container.appendChild(div);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    </script>

    <!-- App JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>
</html>