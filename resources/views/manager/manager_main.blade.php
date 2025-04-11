@extends('layouts.manager')

@section('content')
<!-- Main Container -->
<div class="container mx-auto p-6">
    <!-- Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Left Column: Welcome Message + Statistics -->
        <div class="space-y-6">
            
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                    Welcome, {{ Auth::guard('manager')->user()->name }}!
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    You are logged in as 
                    @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                        a <span class="font-semibold dark:text-gray-300">Pre Approval Manager</span>.
                    @elseif(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                        a <span class="font-semibold dark:text-gray-300">Final Approval Manager</span>.
                    @endif
                </p>
            </div>

<!-- New Requests Today -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">New Requests Today</h3>
        <span class="text-sm text-gray-500 dark:text-gray-400">Today</span>
    </div>
    <p id="new-requests-today" class="text-4xl font-bold text-purple-600 dark:text-purple-400 mt-2">
        {{ $newRequestsToday }}
    </p>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Updated in real-time</p>
</div>


            <!-- Two-Column Container: Pending Requests + Pending Final Requests -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Pending Pre-Approval Requests (Only for Pre-Approval Managers) -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pending Pre-Approvals</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Manager {{ Auth::guard('manager')->user()->manager_number }}</span>
                        </div>
                        <p id="pending-requests" class="text-4xl font-bold text-yellow-500 dark:text-yellow-400 mt-2">{{ $pendingRequests }}</p>
                    </div>
                @endif

                <!-- Pending Final Requests (Only for Final Approval Managers) -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pending Final Approvals</h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Manager {{ Auth::guard('manager')->user()->manager_number }}</span>
                        </div>
                        <p id="pending-final-requests" class="text-4xl font-bold text-blue-500 dark:text-blue-400 mt-2">{{ $pendingFinalRequests }}</p>
                    </div>
                @endif

            </div>
        </div>

        <!-- Right Column: Recent Activity (Full Height + Scrollable) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg flex flex-col h-full" style="min-height: 300px;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Recent Activity</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">Latest</span>
            </div>
            
            <!-- Scrollable Activity List -->
            <div id="recent-activities-container" 
                class="overflow-y-auto flex-grow border border-gray-200 dark:border-gray-600 rounded-lg p-4"
                style="max-height: 430px;  overflow-y: scroll;">

                <ul id="recent-activities-list" class="space-y-4">
    @foreach($recentActivities as $activity)
        @php
            $badgeColor = "bg-gray-200 text-gray-700"; // Default color
            if (strtolower($activity->type) === "rejection" || str_contains(strtolower($activity->type), "reject")) {
                $badgeColor = "bg-red-200 dark:bg-red-400 text-red-800 dark:text-red-900";  // Rejected -> Red
            } elseif (strtolower($activity->type) === "approval" || str_contains(strtolower($activity->type), "approve")) {
                $badgeColor = "bg-green-200 dark:bg-green-400 text-green-800 dark:text-green-900";  // Approved -> Green
            }
        @endphp
        <li class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
            data-timestamp="{{ $activity->created_at->timestamp }}">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $activity->description }} <!-- Use the description directly -->
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ $activity->created_at->diffForHumans() }} | 
                    <span class="font-semibold">{{ ucfirst($activity->request_type) }}</span>
                </p>
            </div>
            <span class="text-xs px-2 py-1 rounded-full {{ $badgeColor }}">
                {{ ucfirst($activity->type) }}
            </span>
        </li>
    @endforeach
</ul>
            </div>
        </div>
    </div>
</div>



<!-- Pusher Script for Real-Time Updates -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    // Listen for new requests
    var requestsChannel = pusher.subscribe('requests-channel');
    requestsChannel.bind('new-request', function(data) {
        document.getElementById('new-requests-today').innerText = data.newRequestsToday;
    });

    // Listen for final request status updates
    var finalRequestsChannel = pusher.subscribe('finalrequests-channel');
    finalRequestsChannel.bind('status-updated', function(data) {
        // Update the pending final approval requests count
        document.getElementById('pending-final-requests').innerText = data.pendingFinalRequests;
    });

    // Subscribe to Activities Channel
    var activitiesChannel = pusher.subscribe('activities-channel');

    activitiesChannel.bind('new-activity', function(data) {
    let activity = data.activity;

    let type = activity.type.toLowerCase();

    let badgeColor = "bg-gray-200 text-gray-700"; 
    if (type === "rejection" || type.includes("reject")) {
        badgeColor = "bg-red-200 text-red-800";
    } else if (type === "approval" || type.includes("approve")) {
        badgeColor = "bg-green-200 text-green-800";
    }

    let newActivityItem = document.createElement('li');
    newActivityItem.className = "flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200";
    
    newActivityItem.innerHTML = `
    <div class="flex-1">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
            ${activity.description} <!-- Use the description directly -->
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Just now | 
            <span class="font-semibold">${activity.request_type}</span>
        </p>
    </div>
    <span class="text-xs px-2 py-1 font-semibold rounded-full ${badgeColor}">
        ${activity.type}
    </span>
`;


    let activityList = document.getElementById('recent-activities-list');
    activityList.prepend(newActivityItem);

    let container = document.getElementById('recent-activities-container');
    container.style.overflowY = "scroll"; 

    setTimeout(() => {
        container.scrollTop = 0; 
    }, 100);
});

    document.addEventListener('DOMContentLoaded', function () {
        const activityItems = document.querySelectorAll('#recent-activities-list li');
        const fiveDaysAgo = Date.now() - (5 * 24 * 60 * 60 * 1000);

        activityItems.forEach(item => {
            const timestamp = parseInt(item.getAttribute('data-timestamp')) * 1000; 
            if (timestamp < fiveDaysAgo) {
                item.remove(); 
            }
        });
    });
</script>
@endsection