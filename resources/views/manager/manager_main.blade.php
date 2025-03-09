@extends('layouts.manager')

@section('content')
    <!-- Main Container -->
    <div class="container mx-auto p-6">
        <!-- Two-Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Welcome Message + Statistics -->
            <div class="space-y-6">
                <!-- Welcome Message -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::guard('manager')->user()->name }}!</h1>
                    <p class="text-gray-600 mt-2">You are logged in as a manager.</p>
                </div>

                <!-- New Requests Today -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">New Requests Today</h3>
                        <span class="text-sm text-gray-500">Today</span>
                    </div>
                    <p id="new-requests-today" class="text-4xl font-bold text-purple-600 mt-2">{{ $newRequestsToday }}</p>
                    <p class="text-sm text-gray-500 mt-2">Updated in real-time</p>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                        <span class="text-sm text-gray-500">For Manager {{ Auth::guard('manager')->user()->manager_number }}</span>
                    </div>
                    <p id="pending-requests" class="text-4xl font-bold text-yellow-500 mt-2">{{ $pendingRequests }}</p>
                </div>
            </div>

            <!-- Right Column: Recent Activity (Full Height + Scrollable) -->
            <div class="bg-white p-6 rounded-xl shadow-lg flex flex-col h-full" style="min-height: 600px;">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Recent Activity</h3>
                    <span class="text-sm text-gray-500">Latest</span>
                </div>
                
                <!-- Scrollable Activity List -->
                <div id="recent-activities-container" 
     class="overflow-y-auto flex-grow border border-gray-200 rounded-lg p-4"
     style="max-height: 550px; min-height: 300px; overflow-y: scroll;">

    <ul id="recent-activities-list" class="space-y-4">
        @foreach($recentActivities as $activity)
            @php
                $badgeColor = "bg-gray-200 text-gray-700"; // Default color
                if (strtolower($activity->type) === "rejection" || str_contains(strtolower($activity->type), "reject")) {
                    $badgeColor = "bg-red-200 text-red-800";  // Rejected -> Red
                } elseif (strtolower($activity->type) === "approval" || str_contains(strtolower($activity->type), "approve")) {
                    $badgeColor = "bg-green-200 text-green-800";  // Approved -> Green
                }
            @endphp
            <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
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
            console.log("New request count received:", data.newRequestsToday); // Debugging
            document.getElementById('new-requests-today').innerText = data.newRequestsToday;
        });

      // Subscribe to Pusher channel
var activitiesChannel = pusher.subscribe('activities-channel');

activitiesChannel.bind('new-activity', function(data) {
    let activity = data.activity;

    // Convert type to lowercase to avoid case issues
    let type = activity.type.toLowerCase();

    // Determine badge color based on type
    let badgeColor = "bg-gray-200 text-gray-700"; // Default (neutral)
    if (type === "rejection" || type.includes("reject")) {
        badgeColor = "bg-red-200 text-red-800";  // Rejected -> Red
    } else if (type === "approval" || type.includes("approve")) {
        badgeColor = "bg-green-200 text-green-800";  // Approved -> Green
    }

    // Create new activity item
    let newActivityItem = document.createElement('li');
    newActivityItem.className = "flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200";
    
    newActivityItem.innerHTML = `
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-700">${activity.description}</p>
            <p class="text-xs text-gray-500 mt-1">Just now</p>
        </div>
        <span class="text-xs px-2 py-1 font-semibold rounded-full ${badgeColor}">
            ${activity.type}
        </span>
    `;

    // Append new activity at the TOP of the list
    let activityList = document.getElementById('recent-activities-list');
    activityList.prepend(newActivityItem); // Use prepend to add new activity at the top

    // Ensure scrolling appears when the list grows
    let container = document.getElementById('recent-activities-container');
    container.style.overflowY = "scroll"; // Ensure scrollbar is always present

    // Auto-scroll to top when a new activity is added
    setTimeout(() => {
        container.scrollTop = 0; // Scroll to the top to show the latest activity
    }, 100);
});
    </script>
@endsection
