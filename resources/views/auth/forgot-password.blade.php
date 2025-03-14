<x-guest-layout>
    <!-- Background Image (Fixed & Full-Screen) -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('img/back.jpg') }}');"></div>

    <!-- Dark Overlay for Better Contrast -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Password Reset Form Container -->
    <div class="relative w-full max-w-md mx-auto mt-20 bg-white p-6 rounded-lg shadow-2xl z-10">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Back to login') }}
                </a>

                <x-primary-button>
                    {{ __('Send Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
