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
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-6">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
</x-guest-layout>
