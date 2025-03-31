@extends('layouts.manager')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">📢 Notifications</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        @forelse($notifications as $notification)
            <div class="border-b dark:border-gray-700 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition 
                        {{ $notification->unread() ? 'bg-blue-50 dark:bg-blue-900' : '' }}">

                <a href="{{ $notification->data['url'] ?? '#' }}" 
                   class="block"
                   onclick="event.preventDefault();
                            markNotificationAsRead('{{ $notification->id }}');
                            window.location.href = this.href;">

                    <div class="flex justify-between items-start">
                        <!-- Left Section -->
                        <div class="flex-1">
                            <!-- ✅ Title -->
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                {{ $notification->data['title'] ?? 'No Title' }}
                            </h2>

                            <!-- ✅ Message -->
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ $notification->data['message'] ?? 'No message available' }}
                            </p>

                            <!-- ✅ Created At Date & Time -->
                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 0v4M3 11h18M4 21h16a2 2 0 002-2v-5H2v5a2 2 0 002 2z"/>
                                </svg>
                                <span class="mr-2">
                                    Created: {{ \Carbon\Carbon::parse($notification->data['created_at'])->format('M d, Y h:i A') }}
                                </span>

                                <!-- ✅ Time Ago -->
                                <span class="text-xs text-gray-400 dark:text-gray-500">
                                    ({{ $notification->created_at->diffForHumans() }})
                                </span>
                            </div>
                        </div>

                        <!-- Right Section: Time Ago -->
                        <div class="flex-shrink-0 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                <p class="text-lg">😴 No new notifications</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>

<script>
function markNotificationAsRead(notificationId) {
    fetch("{{ route('manager.notifications.mark-as-read') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: notificationId })
    });
}
</script>
@endsection
