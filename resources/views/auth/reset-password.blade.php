<x-guest-layout>
    <!-- Same background/styling as forgot-password.blade.php -->
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300 opacity-100 animate-gradient"></div>
        <!-- Your particle effects here -->
    </div>

    <!-- Main Form Container -->
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>
            
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                           class="w-full px-3 py-2 border rounded-lg" required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-sm"/>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">New Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg" required>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-sm"/>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>