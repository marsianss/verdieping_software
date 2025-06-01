<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - Kitesurf School</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        html, body {
            height: auto;
            min-height: 100vh;
        }

        .sidebar-icon {
            @apply mr-3 text-lg;
        }

        .sidebar-link {
            @apply flex items-center w-full transition-colors duration-150;
        }

        .badge {
            @apply px-2 py-1 text-xs font-bold rounded-full;
        }

        .btn {
            @apply px-4 py-2 rounded font-medium transition-colors duration-150 inline-flex items-center justify-center;
        }

        .btn-primary {
            @apply bg-blue-600 text-white hover:bg-blue-700;
        }

        .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700;
        }

        /* Ensure main content can scroll */
        main {
            overflow-y: auto;
            max-height: none;
        }

        /* Fix for mobile devices */
        @media (max-width: 768px) {
            .flex.min-h-screen {
                min-height: 100vh;
                height: auto;
            }
        }
    </style>
</head>
<body class="bg-gray-100">    <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white transform transition-all duration-300 ease-in-out md:translate-x-0 md:relative md:inset-auto shadow-xl"
             :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <div class="flex items-center justify-between p-5 border-b border-blue-700">
                <h2 class="text-xl font-bold tracking-tight flex items-center">
                    <i class="fas fa-water mr-3"></i>
                    Kitesurf Admin
                </h2>
                <button @click="sidebarOpen = false" class="text-white md:hidden hover:bg-blue-700 p-1 rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="mt-6">
                <ul class="space-y-1">
                    <li class="hover:bg-blue-700 transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link px-5 py-3">
                            <i class="fas fa-tachometer-alt sidebar-icon"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="hover:bg-blue-700 transition-colors duration-150 {{ request()->routeIs('admin.lessons*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.lessons') }}" class="sidebar-link px-5 py-3">
                            <i class="fas fa-book sidebar-icon"></i>
                            Lessons
                        </a>
                    </li>
                    <li class="hover:bg-blue-700 transition-colors duration-150 {{ request()->routeIs('admin.bookings*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.bookings') }}" class="sidebar-link px-5 py-3">
                            <i class="fas fa-calendar-alt sidebar-icon"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="hover:bg-blue-700 transition-colors duration-150 {{ request()->routeIs('admin.users*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.users') }}" class="sidebar-link px-5 py-3">
                            <i class="fas fa-users sidebar-icon"></i>
                            Users
                        </a>
                    </li>

                    <li class="mt-8 px-5 pt-4 border-t border-blue-700 text-xs font-semibold text-blue-300 uppercase">
                        Navigation
                    </li>
                    <li class="hover:bg-blue-700 transition-colors duration-150">
                        <a href="{{ route('dashboard') }}" class="sidebar-link px-5 py-3">
                            <i class="fas fa-arrow-left sidebar-icon"></i>
                            Back to Main
                        </a>
                    </li>
                    <li class="hover:bg-blue-700 transition-colors duration-150">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="sidebar-link px-5 py-3 w-full text-left">
                                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1 bg-gray-50">
            <!-- Top Navigation -->
            <div class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-20">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = !sidebarOpen" class="text-blue-800 md:hidden p-2 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            <div class="hidden md:block">
                                <h1 class="text-xl font-semibold text-gray-900">Admin Dashboard</h1>
                                <p class="text-sm text-gray-500">Manage your kitesurf school operations</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700 font-medium">Welcome back, <span class="font-semibold text-blue-700">{{ auth()->user()->name }}</span></span>
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="p-6 pb-12">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 p-4 mb-6 rounded-lg shadow-sm flex items-center" role="alert">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-lg mr-3"></i>
                        </div>
                        <div>
                            <p class="font-medium">Success!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 mb-6 rounded-lg shadow-sm flex items-center" role="alert">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-lg mr-3"></i>
                        </div>
                        <div>
                            <p class="font-medium">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
