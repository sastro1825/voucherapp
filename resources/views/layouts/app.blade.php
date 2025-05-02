<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Voucher App')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/fbtnew.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex transition-all duration-300">
    <!-- Sidebar -->
    <aside class="bg-white dark:bg-gray-800 shadow-lg h-screen fixed z-20" 
           x-bind:class="{ 'w-64': sidebarOpen, 'w-0': !sidebarOpen, 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="transform -translate-x-full"
           x-transition:enter-end="transform translate-x-0"
           x-transition:leave="transition ease-in duration-300"
           x-transition:leave-start="transform translate-x-0"
           x-transition:leave-end="transform -translate-x-full">
        <div class="p-6" x-show="sidebarOpen">
            <h1 class="text-2xl font-bold mb-6">@yield('sidebar-title')</h1>
            <nav>
                <ul class="space-y-2">
                    @yield('sidebar-menu')
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 transition-all duration-300"
         x-bind:class="{ 'ml-64': sidebarOpen, 'ml-0': !sidebarOpen }">
        <!-- Header with Hamburger Menu and Dark Mode Toggle -->
        <header class="p-4 flex justify-between items-center">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                    class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 focus:outline-none">
                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </header>

        <!-- Content -->
        <main class="p-6">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
@yield('scripts')
</body>
</html>