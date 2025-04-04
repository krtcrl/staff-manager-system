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
<body x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' }" 
      x-init="localStorage.setItem('sidebarOpen', sidebarOpen)" 
      class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">
<!-- Loading Overlay -->
<div id="loading-overlay" 
     class="fixed inset-0 z-50 flex items-center justify-center 
            bg-white dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 hidden">
    
    <div class="animate-spin rounded-full h-16 w-16 
                border-t-4 border-blue-500 dark:border-blue-400"></div>
</div>


<div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
        <div class="p-4 flex justify-between items-center">
            <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">Approval</h2>
            <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" 
                    class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar Links -->
        <ul class="mt-4">
            <li class="mb-2">
                <a href="{{ route('manager.dashboard') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                    <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" fill="none" 
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Dashboard</span>
                </a>
            </li>

            <!-- Request List Link -->
            @php
                $allowedRequestManagers = [1, 2, 3, 4];
                $managerNumber = Auth::guard('manager')->user()->manager_number;
            @endphp
            @if(in_array($managerNumber, $allowedRequestManagers))
                <li class="mb-2">
                    <a href="{{ route('manager.request-list') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 16h8M8 12h8m-8-4h4M4 4h16v16H4z"/>
                        </svg>
                        <span :class="sidebarOpen ? 'block' : 'hidden'">Pre Approval</span>
                    </a>
                </li>
            @endif

            <!-- Final Request List Link -->
            @php
                $allowedFinalRequestManagers = [1, 5, 6, 7, 8, 9];
            @endphp
            @if(in_array($managerNumber, $allowedFinalRequestManagers))
                <li class="mb-2">
                    <a href="{{ route('manager.finalrequest-list') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        <span :class="sidebarOpen ? 'block' : 'hidden'">Final Approval</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Navbar -->
<nav class="bg-white dark:bg-gray-700 shadow-md border-b border-gray-200 dark:border-gray-600 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">

        <!-- Left Section (Logo or Placeholder) -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('manager.dashboard') }}" class="text-blue-600 font-bold text-lg hover:text-blue-700 transition">
                <!-- Placeholder for Logo -->
            </a>
        </div>

        <!-- Middle Section (Notifications) -->
        <div class="flex items-center space-x-4">
     <!-- Notification Bell -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <!-- Dynamic Notification Badge -->
        @auth
        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
        @endauth
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open" @click.away="open = false" 
         class="absolute right-0 mt-2 w-72 md:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-50 transition-all duration-300">

        <div class="px-4 py-3 border-b dark:border-gray-600">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Notifications</h3>
        </div>
<div class="max-h-96 overflow-y-auto">
    @forelse(auth()->guard('manager')->user()->unreadNotifications as $notification)
        <a href="#" 
           class="block px-4 py-3 border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
           onclick="markAsReadAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <!-- Dynamic SVG Icon Based on Notification Type -->
                    @if(($notification->data['type'] ?? '') == 'approval')
                    <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    </svg>
                    @elseif(($notification->data['type'] ?? '') == 'action_required')
                    <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    @elseif(($notification->data['type'] ?? '') == 'completed')
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @elseif(($notification->data['type'] ?? '') == 'new_request')
                    <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5-13H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2z" />
                    </svg>
                    @else
                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @endif
                </div>

                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                        {{ $notification->data['title'] ?? 'No Title' }}
                    </p>
                    
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $notification->data['message'] ?? 'No message available' }}
                    </p>

                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y h:i A') }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
            No new notifications
        </div>
    @endforelse
</div>


    <div class="px-4 py-2 border-t dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-center">
        <a href="{{ route('manager.notifications') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
            View all notifications
        </a>
    </div>
</div>





        </div>

        <!-- Right Section (User Dropdown) -->
        <div class="relative" x-data="{ open: false }">
            <!-- User Info Button -->
            <button @click="open = !open" 
                    class="flex items-center space-x-2 px-4 py-2 
                           bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                           rounded-lg transition">

                <span class="text-black dark:text-gray-300 font-medium">
                    {{ Auth::guard('manager')->user()->name }}
                </span>

                <svg class="w-5 h-5 transition-transform duration-300" 
                     :class="open ? 'rotate-180' : ''" 
                     fill="none" stroke="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" 
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                          clip-rule="evenodd" 
                          class="stroke-current text-gray-600 dark:text-gray-300"></path>
                </svg>
            </button>

            <body x-data="{
                darkMode: localStorage.getItem('darkMode') === 'true',
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                    document.documentElement.classList.toggle('dark', this.darkMode);
                }
            }" x-init="document.documentElement.classList.toggle('dark', darkMode)">

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" 
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-400 dark:border-gray-600 z-50 transition-all duration-300"
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 scale-95" 
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-95">

                <!-- Profile -->
                <div class="px-4 py-3 border-b dark:border-gray-600">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Signed in as</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::guard('manager')->user()->email }}</p>
                </div>

                <!-- Dropdown Links -->
                <div class="py-2">
                    <div class="flex items-center justify-between px-4 py-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Dark Mode</span>
                        <label for="dark-mode-toggle" class="flex items-center cursor-pointer">
                            <!-- Switch -->
                            <div class="relative">
                                <input type="checkbox" id="dark-mode-toggle" class="sr-only">
                                <!-- Slider -->
                                <div class="w-12 h-6 bg-gray-300 dark:bg-gray-700 rounded-full shadow-inner transition">
                                    <div class="absolute w-5 h-5 bg-white dark:bg-gray-400 rounded-full shadow transform transition-all duration-300" id="toggle-indicator"></div>
                                </div>
                            </div>
                            
                            <!-- Sun 🌞 and Moon 🌙 Icons -->
                            <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 text-yellow-500 hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3.75V2a1 1 0 112 0v1.75a1 1 0 01-2 0zM10 18.25V20a1 1 0 102 0v-1.75a1 1 0 00-2 0zM3.75 10H2a1 1 0 100 2h1.75a1 1 0 100-2zM18.25 10H20a1 1 0 100-2h-1.75a1 1 0 100 2zM5.636 5.636a1 1 0 00-1.414 1.414l1.237 1.237a1 1 0 001.414-1.414L5.636 5.636zM15.778 15.778a1 1 0 101.414 1.414l1.237-1.237a1 1 0 00-1.414-1.414l-1.237 1.237zM5.636 15.778a1 1 0 00-1.414 1.414l1.237 1.237a1 1 0 001.414-1.414l-1.237-1.237zM15.778 5.636a1 1 0 101.414-1.414l-1.237-1.237a1 1 0 00-1.414 1.414l1.237 1.237z" />
                            </svg>

                            <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 text-blue-500 hidden" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.293 13.293a8 8 0 11-10.586-10.586 7 7 0 1010.586 10.586zM10 4a6 6 0 100 12A6 6 0 0010 4z" />
                            </svg>
                        </label>
                    </div>
                </div>

                <!-- Logout -->
                <div class="border-t dark:border-gray-600">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-3 bg-white dark:bg-gray-900">
        @yield('content')
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const darkModeToggle = document.getElementById("dark-mode-toggle");

    if (!darkModeToggle) return; // Ensure the button exists to prevent errors

    const isDarkMode = localStorage.getItem("darkMode") === "true";
    document.documentElement.classList.toggle("dark", isDarkMode);

    darkModeToggle.addEventListener("click", () => {
        const newDarkModeState = !document.documentElement.classList.contains("dark");
        document.documentElement.classList.toggle("dark", newDarkModeState);
        localStorage.setItem("darkMode", newDarkModeState);
    });
});

</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const loadingOverlay = document.getElementById('loading-overlay');

        // Handle link clicks
        document.querySelectorAll('a[href]').forEach(link => {
            link.addEventListener('click', (e) => {
                const url = link.getAttribute('href');

                // ✅ Skip loader for anchor links or JavaScript links
                if (url.startsWith('#') || url.startsWith('javascript')) {
                    return;  // Skip loading effect
                }

                // Show the loader and delay navigation
                e.preventDefault();
                loadingOverlay.classList.remove('hidden');

                // Add a slight delay before navigation to prevent flickering
                setTimeout(() => {
                    window.location.href = url;
                }, 300);  // 300ms delay prevents flicker
            });
        });

        // Hide loader after the page fully loads
        window.addEventListener('load', () => {
            loadingOverlay.classList.add('hidden');
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('dark-mode-toggle');
    const toggleIndicator = document.getElementById('toggle-indicator');
    const iconSun = document.getElementById('icon-sun');
    const iconMoon = document.getElementById('icon-moon');

    // Initialize theme based on localStorage
    const isDarkMode = localStorage.getItem('theme') === 'dark';
    
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
        toggle.checked = true;
        iconMoon.classList.remove('hidden');
        iconSun.classList.add('hidden');
        toggleIndicator.classList.add('translate-x-6');  // Slide to dark
    } else {
        document.documentElement.classList.remove('dark');
        toggle.checked = false;
        iconSun.classList.remove('hidden');
        iconMoon.classList.add('hidden');
        toggleIndicator.classList.remove('translate-x-6');  // Slide to light
    }

    // Toggle functionality
    toggle.addEventListener('change', () => {
        const isChecked = toggle.checked;
        
        document.documentElement.classList.toggle('dark', isChecked);
        localStorage.setItem('theme', isChecked ? 'dark' : 'light');

        if (isChecked) {
            iconMoon.classList.remove('hidden');
            iconSun.classList.add('hidden');
            toggleIndicator.classList.add('translate-x-6');  // Slide to dark
        } else {
            iconSun.classList.remove('hidden');
            iconMoon.classList.add('hidden');
            toggleIndicator.classList.remove('translate-x-6');  // Slide to light
        }
    });
});

</script>
@push('scripts')
<script>
    // ✅ Mark as read and immediately redirect
    function markAsReadAndRedirect(notificationId, url) {
        fetch("{{ route('manager.notifications.mark-as-read') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: notificationId })
        }).then(() => {
            // ✅ Redirect to the request details page after marking as read
            window.location.href = url;
        }).catch((error) => {
            console.error('Error marking notification as read:', error);
            window.location.href = url;  // Redirect even if marking fails
        });
    }
</script>

</body>
</html>