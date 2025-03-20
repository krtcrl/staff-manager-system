<x-guest-layout>
    <!-- Background Image (Full-Screen) -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('img/back.jpg') }}');"></div>

    <!-- Dark Overlay for Better Contrast -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Logo Section (Fixed to Top Corners) -->
    <div class="absolute top-4 left-4 z-10">
        <img src="{{ asset('storage/dev-img/NT Logo.png') }}" alt="Left Logo" class="h-16">
    </div>

    <div class="absolute top-4 right-4 z-10">
        <img src="{{ asset('storage/dev-img/Sheldal Logo.png') }}" alt="Right Logo" class="h-16">
    </div>

    <!-- System Name (Custom Font & Centered) -->
    <div class="relative w-full text-center mt-16 z-10">
        <h1 class="text-5xl font-extrabold text-white drop-shadow-lg" style="font-family: 'Montserrat', sans-serif;">
            Standard Time Approval
        </h1>
        <p class="text-lg text-gray-300 mt-2" style="font-family: 'Inter', sans-serif;">
            Streamlined Approval Process for N.T
        </p>
    </div>

    <!-- Login Form Container -->
    <div class="relative w-full max-w-md mx-auto mt-12 bg-white p-8 rounded-lg shadow-2xl z-10">

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div class="mt-6 relative">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                
                <!-- Eye Icon (Initially Hidden) -->
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 mt-6" id="togglePasswordContainer" style="display: none;">
                    <button type="button" id="togglePassword" class="text-gray-500 focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Login Button & Forgot Password -->
            <div class="flex items-center justify-between mt-6">
                <div>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <span class="text-sm text-gray-600">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">{{ __('Register') }}</a>
                </span>
            </div>
        </form>
    </div>

    <!-- Include Font Awesome for the eye icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- JavaScript to toggle password visibility & show eye icon only when typing -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const toggleContainer = document.querySelector('#togglePasswordContainer');

            // Toggle eye icon visibility based on input value
            password.addEventListener('input', function () {
                if (password.value.length > 0) {
                    toggleContainer.style.display = 'flex'; // Show the eye icon
                } else {
                    toggleContainer.style.display = 'none'; // Hide the eye icon
                }
            });

            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle eye icon class
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
</x-guest-layout>
