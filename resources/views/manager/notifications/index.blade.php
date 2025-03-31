@extends('layouts.manager')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        @forelse($notifications as $notification)
            <div class="border-b dark:border-gray-700 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition 
                        {{ $notification->unread() ? 'bg-blue-50 dark:bg-blue-900' : '' }}">
                
                <a href="{{ $notification->data['url'] ?? '#' }}" 
                   class="block"
                   onclick="event.preventDefault();
                            markNotificationAsRead('{{ $notification->id }}');
                            window.location.href = this.href;">
                    
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $notification->data['title'] ?? 'No Title' }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $notification->data['message'] ?? 'No message available' }}
                            </p>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-300">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                </a>
            </div>
        @empty
            <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                No notifications found
            </div>
        @endforelse
    </div>

    <div class="mt-4">
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
