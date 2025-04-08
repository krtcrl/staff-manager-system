@extends('layouts.manager')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">📢 Notifications</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Scrollable container for notifications -->
        <div class="max-h-[calc(100vh-200px)] overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="border-b dark:border-gray-700 px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition 
                            {{ $notification->unread() ? 'bg-blue-50 dark:bg-blue-900' : '' }}">

                    <!-- ✅ Clickable Notification with Mark as Read + Redirect -->
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
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
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
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>

<script>
    // ✅ Mark as Read and Redirect
    function markAsReadAndRedirect(notificationId, url) {
        fetch("{{ route('manager.notifications.mark-as-read') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: notificationId })
        }).then(() => {
            // ✅ Redirect after marking as read
            window.location.href = url;
        }).catch((error) => {
            console.error('Error marking notification as read:', error);
            window.location.href = url;  // Redirect even if marking fails
        });
    }
</script>
@endsection