<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ sidebarOpen: true }" class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
            <div class="p-4 flex justify-between items-center">
                <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">Manager</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Links -->
            <ul class="mt-4">
                <li class="mb-2">
                    <a href="{{ route('manager.dashboard') }}" class="block p-2 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li class="mb-2">
                    <a href="#" class="block p-2 hover:bg-gray-700 rounded">Settings</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">

                    <!-- Placeholder for other navbar content -->
                    <div></div>

                    <!-- Notification & User Dropdown (Side by Side) -->
                    <div class="flex items-center space-x-6">
                        
                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <!-- Notification Badge -->
                                @if(isset($notifications) && $notifications->count() > 0)
                                    <span id="notification-count" class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5">
                                        {{ $notifications->count() ?? 0 }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-md z-50 transition-all duration-200">
                                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">Notifications</h3>
                                </div>
                                <div id="notification-list" class="max-h-64 overflow-y-auto">
                                    @forelse($notifications ?? [] as $notification)
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <div class="flex items-center space-x-2">
                                                <span class="text-blue-500">ℹ</span>
                                                <span>{{ $notification->message }}</span>
                                            </div>
                                        </a>
                                    @empty
                                        <p class="px-4 py-2 text-sm text-gray-500">No new notifications.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span>{{ Auth::guard('manager')->user()->name ?? 'Manager' }}</span>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>