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
            <img src="{{ asset('storage/dev-img/NTLogo.png') }}" alt="NT Logo" class="h-10 md:h-12 drop-shadow-md">
        </div>
        <div class="absolute top-6 right-6 z-20">
            <img src="{{ asset('storage/dev-img/SheldalLogo.png') }}" alt="Sheldal Logo" class="h-10 md:h-12 drop-shadow-md">
        </div>
        
        <!-- System Title with Super Admin Badge -->
        <div class="text-center mb-4 md:mb-8 z-10 w-full max-w-2xl px-4 mt-16 md:mt-0">
         
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 tracking-tight drop-shadow-sm">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-700">System Control</span>
                <span class="block mt-1 md:mt-2 text-gray-700 font-medium text-lg md:text-xl">Administration Panel</span>
            </h1>
            <p class="mt-2 md:mt-3 text-xs sm:text-sm text-gray-600 font-light">
                Elevated access for full system management
            </p>
        </div>

        <!-- Glass Card Login Form with Super Admin Features -->
        <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden border border-white/20 z-10 mb-4 md:mb-8 mx-4" style="max-height: 80vh; overflow-y: auto;">
            <div class="p-6 md:p-8">
                <!-- Security Alert -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                <span class="font-bold">Warning:</span> This portal is restricted to authorized personnel only.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('superadmin.login') }}">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="mb-4 md:mb-6">
                        <label for="email" class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Super Admin Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 md:h-5 w-4 md:w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                class="block w-full pl-9 md:pl-10 pr-3 py-2 md:py-3 text-xs md:text-sm bg-white/80 border border-gray-200/80 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/90 focus:border-transparent transition duration-200 shadow-sm"
                                placeholder="email@gmail.com" value="{{ old('email') }}">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs md:text-sm" />
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 md:mb-6">
                        <label for="password" class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Administration Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 md:h-5 w-4 md:w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-9 md:pl-10 pr-8 md:pr-10 py-2 md:py-3 text-xs md:text-sm bg-white/80 border border-gray-200/80 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/90 focus:border-transparent transition duration-200 shadow-sm"
                                placeholder="••••••••">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                                <svg id="eyeIcon" class="h-4 md:h-5 w-4 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs md:text-sm" />
                    </div>


                    <!-- Login Button -->
                    <button type="submit" class="w-full flex justify-center items-center py-2 md:py-3 px-4 border border-transparent rounded-lg shadow-sm text-xs md:text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </button>

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

    <!-- Password Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle eye icon
                if (type === 'password') {
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                } else {
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                }
            });
        });
    </script>
</x-guest-layout>