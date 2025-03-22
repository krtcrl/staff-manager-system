@extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">
        <!-- Final Request Details Section at the Top -->
        <div class="bg-white dark:bg-gray-800 p-2 rounded-lg shadow-sm mb-2">
            <!-- Title and Created At Timestamp -->
            <div class="flex justify-between items-start mb-1">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                    Final Request Details
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Created: <span id="created-time">{{ $finalRequest->created_at->format('M j, Y, g:i A') }}</span>
                </p>
            </div>

            <!-- Part Number as a Header -->
            <div class="mb-2">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-300">{{ $finalRequest->part_number }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Part Number</p>
            </div>

            <!-- Two Columns for Final Request Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <!-- Left Column -->
                <div class="space-y-0.5">
                    <!-- Unique Code -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Unique Code:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->unique_code }}</span>
                    </div>

                    <!-- Part Name -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Part Name:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->part_name }}</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-0.5">
                    <!-- Status -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Status:</span>
                        @if(str_contains($finalRequest->status, 'Approved by'))
                            <span class="text-green-500 font-semibold">{{ $finalRequest->status }}</span>
                        @elseif(str_contains($finalRequest->status, 'Rejected by'))
                            <span class="text-red-500 font-semibold">{{ $finalRequest->status }}</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400 font-semibold">Pending</span>
                        @endif
                    </div>

                    <!-- Manager Status Section -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Manager Status:</span>
                        <div class="flex space-x-1 mt-0.5">
                            <!-- Approved -->
                            <div class="relative group">
                                <span id="approved-count" class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="approved-managers-list">
                                        @if(count($approvedManagers) > 0)
                                            @foreach($approvedManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one approved this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Rejected -->
                            <div class="relative group">
                                <span id="rejected-count" class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="rejected-managers-list">
                                        @if(count($rejectedManagers) > 0)
                                            @foreach($rejectedManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one rejected this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Pending -->
                            <div class="relative group">
                                <span id="pending-count" class="text-gray-500 dark:text-gray-400 font-semibold">{{ count($pendingManagers) }} Pending</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="pending-managers-list">
                                        @if(count($pendingManagers) > 0)
                                            @foreach($pendingManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one pending actions on this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to List Button -->
            <div class="mt-2 flex space-x-1">
                <a href="{{ route('staff.finallist') }}" 
                   class="px-2 py-0.5 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Final Approval Attachment Section -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Final Approval Attachment</h2>
                @if ($finalRequest->final_approval_attachment)
                    <button id="fullscreen-btn" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition dark:bg-gray-700 dark:hover:bg-gray-800">
                        Full Screen
                    </button>
                @endif
            </div>

            @if ($finalRequest->final_approval_attachment)
                <!-- Added bottom margin and larger padding -->
                <div id="attachment-container" class="h-[600px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-12">
                    <iframe 
                        id="attachment-iframe"
                        src="{{ asset('storage/' . $finalRequest->final_approval_attachment) }}" 
                        class="w-full h-full border rounded-lg">
                    </iframe>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No final approval attachment available.</p>
            @endif
        </div>
    </div>
</div>

<!-- Timezone Conversion Script -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const createdTime = document.getElementById('created-time');
        if (createdTime) {
            const utcTime = "{{ $finalRequest->created_at->format('Y-m-d H:i:s') }}";
            const date = new Date(utcTime + ' UTC');
            
            // Convert to GMT+8
            const options = {
                timeZone: 'Asia/Singapore', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true
            };

            createdTime.textContent = date.toLocaleString('en-US', options);
        }
    });
</script>

<!-- Fullscreen Script -->
<script>
    // Fullscreen functionality for the attachment
    document.getElementById('fullscreen-btn')?.addEventListener('click', function () {
        const iframe = document.getElementById('attachment-iframe');
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { // Firefox
            iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, and Opera
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { // IE/Edge
            iframe.msRequestFullscreen();
        }
    });
</script>
@endsection