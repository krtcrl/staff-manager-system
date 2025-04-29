<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
     <!-- Include XLSX library -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!-- Font Awesome (for eye icons) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>
<body x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true', modalOpen: false, userInput: '' }" 
      x-init="localStorage.setItem('sidebarOpen', sidebarOpen)" 
      class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">
    <div class="flex min-h-screen">

<!-- Loading Overlay -->
<div id="loading-overlay" 
     class="fixed inset-0 z-50 flex items-center justify-center 
            bg-white dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 hidden">
    
    <div class="animate-spin rounded-full h-16 w-16 
                border-t-4 border-blue-500 dark:border-blue-400"></div>
</div>


        
       <!-- Sidebar -->
<div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
    <div class="p-4 flex justify-between items-center">
        <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">Staff Menu</h2>
        <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Button to Open Modal -->
    <div class="px-4">
        <button 
            @click="modalOpen = true; $nextTick(() => { $data.modalComponent.resetForm(); })" 
            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition flex items-center justify-center"
        >
            <!-- Plus Icon -->
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span :class="sidebarOpen ? 'block' : 'hidden'">Request</span>
        </button>
    </div>

    <!-- Sidebar Links -->
    <ul class="mt-4">
        <!-- Dashboard Link -->
        <li class="mb-2">
            <a href="{{ route('staff.dashboard') }}" 
               class="flex items-center p-2 transition-all duration-300 rounded relative group 
                      {{ request()->is('staff/dashboard*') || request()->routeIs('staff.dashboard*') ? 
                         'bg-blue-900 text-white font-semibold shadow-lg' : 
                         'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6 mx-auto'" 
                     xmlns="http://www.w3.org/2000/svg" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden'">Dashboard</span>
                <!-- Active indicator bar -->
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-400 rounded-r {{ request()->is('staff/dashboard*') || request()->routeIs('staff.dashboard*') ? 'opacity-100' : 'opacity-0' }}"></span>
            </a>
        </li>

        <!-- Pre Approval Link -->
        <li class="mb-2">
            <a href="{{ route('staff.prelist') }}" 
               class="flex items-center p-2 transition-all duration-300 rounded relative group 
                      {{ request()->is('staff/pre*') || request()->routeIs('staff.pre*') || 
                         request()->is('staff/prelist*') || request()->routeIs('staff.prelist*') || 
                         request()->is('staff/request*') || request()->routeIs('staff.request*') || 
                         request()->is('staff/request_details*') || request()->routeIs('staff.request.details*') ? 
                         'bg-blue-900 text-white font-semibold shadow-lg' : 
                         'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6 mx-auto'" 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M8 16h8M8 12h8m-8-4h4M4 4h16v16H4z"/>
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden'">Pre Approval</span>
                <!-- Active indicator bar -->
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-400 rounded-r {{ 
                    request()->is('staff/pre*') || request()->routeIs('staff.pre*') || 
                    request()->is('staff/prelist*') || request()->routeIs('staff.prelist*') || 
                    request()->is('staff/request*') || request()->routeIs('staff.request*') || 
                    request()->is('staff/request_details*') || request()->routeIs('staff.request.details*') ? 
                    'opacity-100' : 'opacity-0' 
                }}"></span>
            </a>
        </li>

        <!-- Final Request List Link -->
        <li class="mb-2">
            <a href="{{ route('staff.finallist') }}" 
               class="flex items-center p-2 transition-all duration-300 rounded relative group 
                      {{ request()->is('staff/final*') || request()->routeIs('staff.final*') || 
                         request()->is('staff/finallist*') || request()->routeIs('staff.finallist*') || 
                         request()->is('staff/final_details*') || request()->routeIs('staff.final.details*') ? 
                         'bg-blue-900 text-white font-semibold shadow-lg' : 
                         'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6 mx-auto'" 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden'">Final Approval</span>
                <!-- Active indicator bar -->
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-400 rounded-r {{ 
                    request()->is('staff/final*') || request()->routeIs('staff.final*') || 
                    request()->is('staff/finallist*') || request()->routeIs('staff.finallist*') || 
                    request()->is('staff/final_details*') || request()->routeIs('staff.final.details*') ? 
                    'opacity-100' : 'opacity-0' 
                }}"></span>
            </a>
        </li>

        <!-- Request History Link -->
        <li class="mb-2">
            <a href="{{ route('staff.request.history') }}" 
               class="flex items-center p-2 transition-all duration-300 rounded relative group 
                      {{ request()->is('staff/request/history*') || request()->routeIs('staff.request.history*') || 
                         request()->is('staff/history*') || request()->routeIs('staff.history*') ? 
                         'bg-blue-900 text-white font-semibold shadow-lg' : 
                         'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6 mx-auto'" 
                    xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-6-8.72"/>
                </svg>
                <span :class="sidebarOpen ? 'block' : 'hidden'">Request History</span>
                <!-- Active indicator bar -->
                <span class="absolute left-0 top-0 h-full w-1 bg-blue-400 rounded-r {{ 
                    request()->is('staff/request/history*') || request()->routeIs('staff.request.history*') || 
                    request()->is('staff/history*') || request()->routeIs('staff.history*') ? 
                    'opacity-100' : 'opacity-0' 
                }}"></span>
            </a>
        </li>
    </ul>
</div>


       <!-- Main Content -->
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-700 shadow-md border-b border-gray-200 dark:border-gray-600 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <!-- Left Section (Logo or Menu) -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('staff.dashboard') }}" class="text-blue-600 font-bold text-lg hover:text-blue-700 transition">
                    <!-- Logo or icon placeholder -->
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
            <!-- Notification Badge - Only shown when there are unread notifications -->
            @auth('staff')
                @php
                    $unreadCount = Auth::guard('staff')->user()->unreadNotifications->count();
                @endphp
                @if($unreadCount > 0)
                    <span id="notification-badge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            @endauth
        </button>

        <!-- Notification Dropdown -->
        <div x-show="open" @click.away="open = false" 
             class="absolute right-0 mt-2 w-72 md:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-50 transition-all duration-300">
            
            <div class="px-4 py-3 border-b dark:border-gray-600">
                <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Notifications</h3>
            </div>
            
            <div class="max-h-96 overflow-y-auto" id="notification-list">
    @auth('staff')
        @forelse(Auth::guard('staff')->user()->unreadNotifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" 
               class="block px-4 py-3 border-b dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition notification-item"
               onclick="event.preventDefault(); markAsReadAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <!-- Dynamic SVG Icon Based on Notification Type -->
                        @if(($notification->data['type'] ?? '') == 'approval')
                        <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                        @elseif(($notification->data['type'] ?? '') == 'progress')
                        <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        @elseif(($notification->data['type'] ?? '') == 'final_approval')
                        <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        @elseif(($notification->data['type'] ?? '') == 'completion')
                        <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @elseif(($notification->data['type'] ?? '') == 'rejected')
<svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
</svg>

                        @else
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ $notification->data['title'] ?? 'New Notification' }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        <div class="flex justify-between mt-1">
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
    @endauth
</div>

            
            <!-- View All Notifications Link -->
            <div class="px-4 py-2 border-t dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-center">
                <a href="{{ route('staff.notifications') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    View all notifications
                </a>
            </div>
        </div>
    </div>

     
<script>
function markAsReadAndRedirect(notificationId, url) {
    // Show loading overlay if you have one
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) loadingOverlay.classList.remove('hidden');

    fetch('/staff/notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id: notificationId
        })
    })
    .then(response => {
        if (response.ok) {
            // Remove the notification from UI
            const notificationElement = document.querySelector(`.notification-item[onclick*="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.remove();
            }
            
            // Update badge count
            updateNotificationBadge();
            
            // Redirect to target URL
            window.location.href = url;
        } else {
            // Fallback redirect if marking fails
            window.location.href = url;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = url;
    })
    .finally(() => {
        if (loadingOverlay) loadingOverlay.classList.add('hidden');
    });
}

function updateNotificationBadge() {
    const badge = document.getElementById('notification-badge');
    const notifications = document.querySelectorAll('.notification-item');
    
    if (notifications.length === 0) {
        // Remove badge if no notifications left
        if (badge) {
            badge.remove();
        }
    } else {
        // Update badge count if notifications remain
        if (badge) {
            badge.textContent = notifications.length;
        }
    }
}
</script>
                    <!-- Right Section (User Profile & Dropdown) -->
                    <div class="relative" x-data="{ open: false }">
                        <!-- User Info Button -->
                        <button @click="open = !open" 
        class="flex items-center space-x-2 px-4 py-2 
               bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
               rounded-lg transition">

    <!-- User Name -->
    <span class="text-black dark:text-gray-300 font-medium">
        {{ Auth::guard('staff')->user()->name }}
    </span>

    <!-- Dropdown Icon -->
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
}" 
    x-init="document.documentElement.classList.toggle('dark', darkMode)">

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
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::guard('staff')->user()->email }}</p>
    </div>

   <!-- Dropdown Links -->
<div class="py-2">
<a href="{{ route('staff.password.change.form') }}" 
   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
    Change Password
</a>
    <!-- 🌙🌞 Dark Mode Switch -->
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

</body>


                    </div>
                </div>
            </nav>

           <!-- Content Area -->
           <div class="flex-1 overflow-y-auto p-5 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto">
        @yield('content')
    </div>
</div>

        </div>
    </div>

    <!-- Pass part data from Laravel to JavaScript -->
    <script>
        window.partsData = @json($parts ?? []); // Use empty array as fallback
    </script>

 <!-- Modal -->
<div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
    <div class="bg-white p-6 rounded-lg shadow-lg transition-all duration-300 ease-in-out flex flex-col w-[500px]" 
         x-data="modalComponent">
        
        <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data" @submit.prevent="submitForm">
            @csrf
            <!-- Step 1: Basic Information -->
            <div x-show="step === 1">
                <h2 class="text-lg font-semibold mb-4">Step 1: Basic Information</h2>

                <!-- Auto-Generated Code -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Auto-Generated Code</label>
                    <div class="p-3 bg-gray-100 border rounded text-center font-semibold text-blue-600">
                        <span x-text="uniqueCode"></span>
                    </div>
                </div>

                <!-- Part Number Combobox -->
                <div class="mb-4">
                    <label for="partNumber" class="block text-sm font-medium text-gray-700">Part Number <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="partNumber" 
                        list="partNumberList" 
                        x-model="partNumberSearch" 
                        @input="filterParts()" 
                        @change="setSelectedPart($event.target.value)" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                        placeholder="Type or select a part number"
                        autocomplete="off"
                        required
                    >
                    <datalist id="partNumberList">
                        <template x-for="part in filteredParts" :key="part.part_number">
                            <option :value="part.part_number" x-text="part.part_number"></option>
                        </template>
                    </datalist>
                    <p class="text-xs text-gray-500 mt-1">If the part number is not listed, you can manually enter it</p>
                </div>

                <!-- Part Name (editable if part not found in database) -->
                <div class="mb-4">
                    <label for="partName" class="block text-sm font-medium text-gray-700">Part Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="partName" 
                        name="partName" 
                        x-model="partName" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                        :readonly="isExistingPart"
                        :class="isExistingPart ? 'bg-gray-100' : ''"
                        required
                    >
                    <p x-show="!isExistingPart" class="text-xs text-blue-500 mt-1">New part will be added to the database</p>
                </div>

                <!-- Part Description (Optional) -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        x-model="description" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                        placeholder="Add any additional description about the part"
                        rows="3"
                    ></textarea>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Cancel
                    </button>
                    <button type="button" @click="nextStep" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2: Attachments -->
            <div x-show="step === 2">
                <h2 class="text-lg font-semibold mb-4">Step 2: Attachments</h2>

                <!-- Pre Approval Attachment -->
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">
                        Pre Approval Attachment (Excel only, max 20MB) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        id="attachment" 
                        name="attachment" 
                        accept=".xls, .xlsx" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
                        required
                        @change="validateExcelFile($event, 'attachmentError')"
                        x-ref="attachment"
                    >
                    <p x-show="attachmentError" class="text-red-500 text-sm mt-1" x-text="attachmentError"></p>
                </div>

                <!-- Final Approval Attachment -->
                <div class="mb-4">
                    <label for="finalApprovalAttachment" class="block text-sm font-medium text-gray-700">
                        Final Approval Attachment (Excel only, max 20MB) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        id="finalApprovalAttachment" 
                        name="final_approval_attachment"  
                        accept=".xls, .xlsx"
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
                        required
                        @change="validateExcelFile($event, 'finalApprovalError')"
                        x-ref="finalApprovalAttachment"
                    >
                    <p x-show="finalApprovalError" class="text-red-500 text-sm mt-1" x-text="finalApprovalError"></p>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Cancel
                    </button>
                    <div class="flex space-x-2">
                        <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                            Previous
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const loadingOverlay = document.getElementById('loading-overlay');

    // Handle link clicks
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', (e) => {
            const url = link.getAttribute('href');

            // Skip loader for anchor links, JavaScript links, or links with `.no-loading`
            if (url.startsWith('#') || url.startsWith('javascript') || link.classList.contains('no-loading')) {
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

function generateCode() {
    return 'ST-' + Math.floor(100000 + Math.random() * 900000);
}

document.addEventListener('alpine:init', () => {
    Alpine.data('modalComponent', () => ({
        step: 1,
        uniqueCode: generateCode(),
        selectedPart: '',
        partNumberSearch: '',
        partName: '',
        description: '',
        parts: window.partsData || [],
        filteredParts: window.partsData || [],
        attachmentError: null,
        finalApprovalError: null,
        isExistingPart: false,

        init() {
            this.uniqueCode = generateCode();
        },

        filterParts() {
            if (this.partNumberSearch) {
                this.filteredParts = this.parts
                    .filter(part => 
                        part.part_number.toLowerCase().includes(this.partNumberSearch.toLowerCase()))
                    .slice(0, 3);
            } else {
                this.filteredParts = this.parts;
            }
        },

        setSelectedPart(value) {
            const selectedPartObj = this.parts.find(part => part.part_number === value);
            if (selectedPartObj) {
                this.selectedPart = value;
                this.partName = selectedPartObj.part_name;
                this.description = selectedPartObj.description || '';
                this.isExistingPart = true;
            } else {
                this.selectedPart = value;
                this.partName = '';
                this.description = '';
                this.isExistingPart = false;
            }
        },

        nextStep() {
            if (this.step === 1) {
                // Validate required fields
                if (!this.partNumberSearch) {
                    alert("Please enter a part number.");
                    return;
                }
                
                if (!this.partName) {
                    alert("Please enter a part name.");
                    return;
                }
                
                // Set the selected part to the manually entered value if not found
                if (!this.isExistingPart) {
                    this.selectedPart = this.partNumberSearch;
                }
            }
            this.step++;
        },

        prevStep() {
            this.step--;
        },

        validateExcelFile(event, errorField) {
            const file = event.target.files[0];
            const allowedTypes = [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];
            const maxSize = 20 * 1024 * 1024; // 20MB

            if (!file) {
                this[errorField] = 'Please select a file.';
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                this[errorField] = 'Only Excel files (.xls, .xlsx) are allowed.';
                return false;
            }

            if (file.size > maxSize) {
                this[errorField] = 'File size must be less than 20MB.';
                return false;
            }

            this[errorField] = null;
            return true;
        },

        submitForm() {
            // Validate all required fields
            if (!this.selectedPart || !this.partName) {
                alert("Please fill in all required fields.");
                return;
            }

            // Validate attachments
            const attachmentInput = this.$refs.attachment;
            const finalApprovalInput = this.$refs.finalApprovalAttachment;
            
            if (!attachmentInput.files.length || !finalApprovalInput.files.length) {
                alert("Both attachments are required.");
                return;
            }

            // Validate file types and sizes
            if (!this.validateExcelFile({ target: attachmentInput }, 'attachmentError') || 
                !this.validateExcelFile({ target: finalApprovalInput }, 'finalApprovalError')) {
                return;
            }

            // Create FormData
            const formData = new FormData();
            formData.append('unique_code', this.uniqueCode);
            formData.append('part_number', this.selectedPart);
            formData.append('part_name', this.partName);
            formData.append('description', this.description);
            formData.append('is_new_part', !this.isExistingPart ? 'true' : 'false');
                        formData.append('attachment', attachmentInput.files[0]);
            formData.append('final_approval_attachment', finalApprovalInput.files[0]);

            // Show loading overlay
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) loadingOverlay.classList.remove('hidden');

            // Submit the form
            fetch("{{ route('requests.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: formData 
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => { 
                        throw new Error(data.message || "Failed to submit request.") 
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    this.modalOpen = false;
                    this.resetForm();
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || "Failed to submit. Please try again.");
            })
            .finally(() => {
                if (loadingOverlay) loadingOverlay.classList.add('hidden');
            });
        },

        resetForm() {
            this.step = 1;
            this.uniqueCode = generateCode();
            this.selectedPart = '';
            this.partNumberSearch = '';
            this.partName = '';
            this.description = '';
            this.isExistingPart = false;
            this.attachmentError = null;
            this.finalApprovalError = null;
            this.$refs.attachment.value = '';
            this.$refs.finalApprovalAttachment.value = '';
        }
    }));
});

// Dark mode toggle logic
document.addEventListener("DOMContentLoaded", () => {
    const darkModeToggle = document.getElementById("dark-mode-toggle");
    const toggleIndicator = document.getElementById('toggle-indicator');
    const iconSun = document.getElementById('icon-sun');
    const iconMoon = document.getElementById('icon-moon');

    if (!darkModeToggle) return;

    // Initialize theme based on localStorage
    const isDarkMode = localStorage.getItem('theme') === 'dark';
    
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
        darkModeToggle.checked = true;
        iconMoon.classList.remove('hidden');
        iconSun.classList.add('hidden');
        if (toggleIndicator) toggleIndicator.classList.add('translate-x-6');
    } else {
        document.documentElement.classList.remove('dark');
        darkModeToggle.checked = false;
        iconSun.classList.remove('hidden');
        iconMoon.classList.add('hidden');
        if (toggleIndicator) toggleIndicator.classList.remove('translate-x-6');
    }

    // Toggle functionality
    darkModeToggle.addEventListener('change', () => {
        const isChecked = darkModeToggle.checked;
        
        document.documentElement.classList.toggle('dark', isChecked);
        localStorage.setItem('theme', isChecked ? 'dark' : 'light');

        if (isChecked) {
            iconMoon.classList.remove('hidden');
            iconSun.classList.add('hidden');
            if (toggleIndicator) toggleIndicator.classList.add('translate-x-6');
        } else {
            iconSun.classList.remove('hidden');
            iconMoon.classList.add('hidden');
            if (toggleIndicator) toggleIndicator.classList.remove('translate-x-6');
        }
    });
});
</script>

</body>
</html>