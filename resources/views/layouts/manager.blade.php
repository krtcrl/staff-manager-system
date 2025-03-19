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
        <nav class="bg-white shadow-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">

                <!-- Left Section (Logo or Placeholder) -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('manager.dashboard') }}" class="text-blue-600 font-bold text-lg hover:text-blue-700 transition">
                        <!-- Placeholder for Logo -->
                    </a>
                </div>

                <!-- Right Section (User Dropdown) -->
                <div class="relative" x-data="{ open: false }">
                    
                    <!-- User Info Button -->
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 bg-gray-200 hover:bg-gray-400 rounded-lg transition">
                        <span class="text-black font-medium">{{ Auth::guard('manager')->user()->name }}</span>
                        <svg class="w-5 h-5 transition-transform duration-300" 
                             :class="open ? 'rotate-180' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" 
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-400 z-50 transition-all duration-300"
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95">

                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-medium text-gray-700">Signed in as</p>
                            <p class="text-sm text-gray-500">{{ Auth::guard('manager')->user()->email }}</p>
                        </div>

                        <div class="border-t">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition">
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>