@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Change Password</h2>
            
            {{-- Display success message if exists --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-600 border border-green-400 dark:border-green-500 text-green-700 dark:text-green-100 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            {{-- Display validation errors --}}
            @if($errors->any())
                <div class="bg-red-100 dark:bg-red-600 border border-red-400 dark:border-red-500 text-red-700 dark:text-red-100 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('staff.password.change') }}">
                @csrf
                
                {{-- Current Password --}}
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">
                        Current Password
                    </label>
                    <input type="password" name="current_password" id="current_password" 
                           class="w-full px-3 py-2 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                           value="{{ old('current_password') }}" required autofocus>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- New Password --}}
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <input type="password" name="new_password" id="new_password" 
                               class="w-full px-3 py-2 border {{ $errors->has('new_password') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                               required>
                        <button type="button" class="absolute right-3 top-2 text-gray-500 dark:text-gray-400" id="toggleNewPassword">
                            <i class="fas fa-eye-slash"></i>  {{-- Closed eye icon --}}
                        </button>
                    </div>
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Confirm New Password --}}
                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-gray-700 dark:text-gray-200 text-sm font-medium mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                               class="w-full px-3 py-2 border {{ $errors->has('new_password_confirmation') ? 'border-red-500' : 'border-gray-300' }} dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                               required>
                        <button type="button" class="absolute right-3 top-2 text-gray-500 dark:text-gray-400" id="toggleConfirmPassword">
                            <i class="fas fa-eye-slash"></i>  {{-- Closed eye icon --}}
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle visibility for New Password
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        var passwordField = document.getElementById('new_password');
        var icon = this.querySelector('i');
        var type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        
        // Toggle the eye icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Toggle visibility for Confirm New Password
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        var confirmPasswordField = document.getElementById('new_password_confirmation');
        var icon = this.querySelector('i');
        var type = confirmPasswordField.type === 'password' ? 'text' : 'password';
        confirmPasswordField.type = type;
        
        // Toggle the eye icon
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });
</script>
@endsection
