@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">📢 Notifications</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Scrollable container for notifications -->
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="border-b dark:border-gray-700 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition 
                            {{ $notification->unread() ? 'bg-blue-50 dark:bg-blue-900' : '' }}">

                    <!-- Clickable Notification with Mark as Read + Redirect -->
                    <a href="#" 
                       class="block hover:text-blue-500 transition"
                       onclick="markAsReadAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}')">

                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $notification->data['title'] ?? 'No Title' }}
                                </h2>

                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ $notification->data['message'] ?? 'No message available' }}
                                </p>

                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $notification->created_at->format('M d, Y h:i A') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            @if($notification->unread())
                                <span class="ml-4 inline-flex items-center justify-center w-3 h-3 rounded-full bg-blue-500"></span>
                            @endif
                        </div>
                    </a>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                    <p class="text-lg">😴 No notifications found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

<script>
    // Mark as Read and Redirect
    function markAsReadAndRedirect(notificationId, url) {
        fetch("/staff/notifications/" + notificationId + "/mark-as-read", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: notificationId })
        }).then(() => {
            window.location.href = url;
        }).catch((error) => {
            console.error('Error marking notification as read:', error);
            window.location.href = url;  // Redirect even if marking fails
        });
    }
</script>
@endsection