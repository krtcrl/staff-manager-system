<x-guest-layout>
    <!-- Same background/styling as forgot-password.blade.php -->
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 opacity-100 animate-gradient"></div>
        <!-- Your particle effects here -->
        <div class="absolute inset-0 opacity-20 animate-pulse-slow">
            <div class="absolute top-1/4 left-1/4 w-32 h-32 rounded-full bg-blue-200 blur-2xl"></div>
            <div class="absolute top-1/3 right-1/4 w-40 h-40 rounded-full bg-blue-300 blur-2xl"></div>
            <div class="absolute bottom-1/4 right-1/3 w-36 h-36 rounded-full bg-blue-400 blur-2xl"></div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 mx-4">
            <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>
            
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                           required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm"/>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" 
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        <button type="button" onclick="togglePasswordVisibility('password', 'password-toggle')" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                                id="password-toggle">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm"/>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" 
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-toggle')" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                                id="confirm-toggle">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-md">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePasswordVisibility(fieldId, buttonId) {
            const passwordField = document.getElementById(fieldId);
            const toggleButton = document.getElementById(buttonId);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleButton.innerHTML = `
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>`;
            } else {
                passwordField.type = 'password';
                toggleButton.innerHTML = `
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>`;
            }
        }
    </script>
</x-guest-layout>