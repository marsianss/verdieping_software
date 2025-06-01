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
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-800 text-white transform transition-all duration-300 ease-in-out" 
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" 
            @click.away="sidebarOpen = false">
            
            <div class="flex items-center justify-between p-4 border-b border-blue-700">
                <h2 class="text-xl font-bold">Kitesurf Admin</h2>
                <button @click="sidebarOpen = false" class="text-white md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mt-6">
                <ul>
                    <li class="px-4 py-2 hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-blue-700 {{ request()->routeIs('admin.lessons*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.lessons') }}" class="flex items-center">
                            <i class="fas fa-book mr-3"></i>
                            Lessons
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-blue-700 {{ request()->routeIs('admin.bookings*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.bookings') }}" class="flex items-center">
                            <i class="fas fa-calendar-alt mr-3"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-blue-700 {{ request()->routeIs('admin.users*') ? 'bg-blue-700' : '' }}">
                        <a href="{{ route('admin.users') }}" class="flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            Users
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-blue-700">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <i class="fas fa-arrow-left mr-3"></i>
                            Back to Main
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-blue-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Content -->
        <div class="min-h-screen md:ml-64">
            <!-- Top Navigation -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-blue-800 md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex items-center">
                    <span class="text-gray-800">Welcome, {{ auth()->user()->name }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <main class="container mx-auto p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p>{{ session('error') }}</p>
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