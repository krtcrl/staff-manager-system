<x-guest-layout>
    <!-- Interactive Gradient Background -->
    <div class="fixed inset-0 overflow-hidden">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 opacity-100 animate-gradient"></div>
        
        <!-- Subtle Animated Particles -->
        <div class="absolute inset-0 opacity-20 animate-pulse-slow">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-blue-200 blur-2xl"></div>
            <div class="absolute top-1/3 right-1/4 w-40 h-40 rounded-full bg-blue-300 blur-2xl"></div>
            <div class="absolute bottom-1/4 right-1/3 w-36 h-36 rounded-full bg-blue-400 blur-2xl"></div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="fixed inset-0 flex flex-col items-center justify-center px-4 sm:px-0 overflow-hidden">
        <!-- Top Logos -->
        <div class="absolute top-6 left-6 z-20">
            <img src="{{ asset('storage/dev-img/NT_Logo.png') }}" alt="NT Logo" class="h-10 md:h-12 drop-shadow-md">
        </div>
        <div class="absolute top-6 right-6 z-20">
            <img src="{{ asset('storage/dev-img/Sheldal_Logo.png') }}" alt="Sheldal Logo" class="h-10 md:h-12 drop-shadow-md">
        </div>
        
        <!-- System Title -->
        <div class="text-center mb-4 md:mb-8 z-10 w-full max-w-2xl px-4 mt-16 md:mt-0">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight drop-shadow-sm">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">Standard Time</span>
                <span class="block mt-1 md:mt-2 text-gray-700 font-medium text-lg md:text-xl">Approval System</span>
            </h1>
            <p class="mt-2 md:mt-3 text-xs sm:text-sm text-gray-600 font-light">
                Streamlined approval workflow for efficient planning operations
            </p>
        </div>

        <!-- Glass Card Forgot Password Form -->
        <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden border border-white/20 z-10 mb-4 md:mb-8 mx-4" style="max-height: 80vh; overflow-y: auto;">
            <div class="p-6 md:p-8">
                <!-- Success Message -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 text-xs md:text-sm rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-4 md:mb-6">
                        <label for="email" class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 md:h-5 w-4 md:w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full pl-9 md:pl-10 pr-3 py-2 md:py-3 text-xs md:text-sm bg-white/80 border border-gray-200/80 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/90 focus:border-transparent transition duration-200 shadow-sm"
                                placeholder="you@example.com" value="{{ old('email') }}">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs md:text-sm" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between mt-6">
                        <a class="underline text-xs md:text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                            {{ __('Back to login') }}
                        </a>

                        <button type="submit" class="w-full flex justify-center items-center py-2 md:py-3 px-4 border border-transparent rounded-lg shadow-sm text-xs md:text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                            Send Password Reset Link
                            <svg class="ml-2 -mr-1 w-3 md:w-4 h-3 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="absolute bottom-4 left-0 right-0 text-center text-gray-600 text-xs md:text-sm z-10">
            © {{ date('Y') }} Standard Time Approval System. All rights reserved.
        </div>
    </div>

    <!-- Background Animation Styles -->
    <style>
        .animate-gradient {
            animation: gradientShift 15s ease infinite;
            background-size: 200% 200%;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-pulse-slow {
            animation: pulse 20s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 0.1; }
            50% { opacity: 0.15; }
        }
    </style>
</x-guest-layout>