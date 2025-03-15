<x-guest-layout>
    <!-- Background Image (Full-Screen) -->
    <div class="fixed inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('img/back.jpg') }}');"></div>

    <!-- Dark Overlay for Better Contrast -->
    <div class="fixed inset-0 bg-black opacity-50"></div>

    <!-- Registration Form Container (Centered) -->
    <div class="fixed inset-0 flex items-center justify-center z-10 p-4">
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-2xl max-h-screen overflow-auto">
            
            <!-- Title -->
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">Register</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="staff">Staff</option>
                        <option value="manager">Manager</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Manager Type Selection (Hidden by Default) -->
                <div id="manager-type-container" class="mt-4 hidden">
                    <x-input-label for="manager_type" :value="__('Manager Type')" />
                    <select id="manager_type" name="manager_type" class="block mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2">
                        <option value="" disabled selected>Select Manager Type</option>
                        <option value="pre_approval">Pre-Approval Manager</option>
                        <option value="final_approval">Final Approval Manager</option>
                    </select>
                    <x-input-error :messages="$errors->get('manager_type')" class="mt-2" />
                </div>

                <!-- Register Button & Login Link -->
                <div class="flex items-center justify-between mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ml-3">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript to Toggle Manager Type Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const managerTypeContainer = document.getElementById('manager-type-container');

            roleSelect.addEventListener('change', function () {
                if (roleSelect.value === 'manager') {
                    managerTypeContainer.classList.remove('hidden'); // Show manager type selection
                } else {
                    managerTypeContainer.classList.add('hidden'); // Hide manager type selection
                }
            });
        });
    </script>
</x-guest-layout>