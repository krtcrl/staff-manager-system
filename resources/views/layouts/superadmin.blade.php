<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' }" 
      x-init="localStorage.setItem('sidebarOpen', sidebarOpen)" 
      class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">

<!-- Loading Overlay -->
<div id="loading-overlay" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 hidden">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 dark:border-blue-400"></div>
</div>

<div class="flex min-h-screen">

<!-- Sidebar -->
<div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
    <div class="p-4 flex justify-between items-center">
        <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">SuperAdmin</h2>
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
    <!-- Dashboard Link -->
    <li class="mb-2">
        <a href="{{ route('superadmin.dashboard') }}" 
           class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.dashboard') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Dashboard</span>
            <!-- Animated underline for active state -->
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
            <!-- Hover Glow Effect -->
            <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
        </a>
    </li>
<!-- Request Table Link -->
<li class="mb-2">
    <a href="{{ route('superadmin.request.table') }}" 
       class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.request.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
             xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
        </svg>
        <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Request Table</span>
        <!-- Animated underline for active state -->
        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        <!-- Hover Glow Effect -->
        <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
    </a>
</li>
<!-- Final Request Table Link -->
<li class="mb-2">
    <a href="{{ route('superadmin.finalrequest.table') }}" 
       class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.finalrequest.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
             xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
             d="M16.862 3.487a2.25 2.25 0 013.182 3.182L10 16.713l-4 1 1-4 9.862-10.226zM4 20c.5-1.5 2-2 4-1.5s3 1.5 5.5.5 2.5-2 4.5-1.5" />
        </svg>
        <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Final Request Table</span>
        <!-- Animated underline for active state -->
        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        <!-- Hover Glow Effect -->
        <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
    </a>
</li>

    <!-- Staff Table Link -->
    <li class="mb-2">
        <a href="{{ route('superadmin.staff.table') }}" 
           class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.staff.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Staff Table</span>
            <!-- Animated underline for active state -->
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
            <!-- Hover Glow Effect -->
            <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
        </a>
    </li>

    <!-- Manager Table Link -->
    <li class="mb-2">
        <a href="{{ route('superadmin.manager.table') }}" 
           class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.manager.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Manager Table</span>
            <!-- Animated underline for active state -->
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
            <!-- Hover Glow Effect -->
            <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
        </a>
    </li>

    <!-- Parts Table Link (NEW) -->
    <li class="mb-2">
        <a href="{{ route('superadmin.parts.table') }}" 
           class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.parts.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
            </svg>
            <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Parts Table</span>
            <!-- Animated underline for active state -->
            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
            <!-- Hover Glow Effect -->
            <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
        </a>
    </li>

<!-- Parts Processes Table Link (NEW) -->
<li class="mb-2">
    <a href="{{ route('superadmin.partprocess.table') }}" 
       class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.partprocess.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
       <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
     xmlns="http://www.w3.org/2000/svg" 
     fill="none" viewBox="0 0 24 24" stroke="currentColor">
     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h4v12H4V6zm6 0h4v12h-4V6zm6 0h4v12h-4V6z" />

</svg>
        <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Part Process Type Table</span>
        <!-- Animated underline for active state -->
        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        <!-- Hover Glow Effect -->
        <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
    </a>
</li>
<!-- Request Histories Table Link -->
<li class="mb-2">
    <a href="{{ route('superadmin.requesthistory.table') }}" 
       class="flex items-center p-2 transition-all duration-300 rounded relative group {{ request()->routeIs('superadmin.requesthistory.table') ? 'text-blue-400 font-semibold shadow-lg' : 'text-gray-400 hover:text-white' }}">
        <!-- Archive Icon -->
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2 transform transition-transform duration-200' : 'w-8 h-8 mx-auto'" 
             xmlns="http://www.w3.org/2000/svg" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
             d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span :class="sidebarOpen ? 'block transform transition-all duration-200' : 'hidden'">Request Histories Table</span>
        <!-- Animated underline for active state -->
        <span class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        <!-- Hover Glow Effect -->
        <span class="absolute inset-0 bg-blue-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
    </a>
</li>

</ul>
</div>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Navbar -->
        <nav class="bg-white dark:bg-gray-700 shadow-md border-b border-gray-200 dark:border-gray-600 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">

                <!-- Left Section (Logo or Placeholder) -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('superadmin.dashboard') }}" class="text-blue-600 font-bold text-lg hover:text-blue-700 transition">
                        SuperAdmin Dashboard
                    </a>
                </div>

                <!-- Right Section (User Dropdown) -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 
                                   bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600
                                   rounded-lg transition">

                        <span class="text-black dark:text-gray-300 font-medium">
                            {{ Auth::guard('superadmin')->user()->name }}
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

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-400 dark:border-gray-600 z-50 transition-all duration-300">
                        <div class="px-4 py-3 border-b dark:border-gray-600">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Signed in as</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::guard('superadmin')->user()->email }}</p>
                        </div>

                        <div class="py-2">
    <a href="#" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
        Logout
    </a>

    <form id="logout-form" action="{{ route('superadmin.logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>

                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>
