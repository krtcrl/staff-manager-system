@extends('layouts.manager')

@section('content')
    <!-- Main Container -->
    <div class="container mx-auto p-6">
        <!-- Welcome Message -->
        <div class="mb-8 text-center lg:text-left">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::guard('manager')->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">You are logged in as a manager.</p>
        </div>

        <!-- Grid Layout for Statistics and Request List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Statistics -->
            <div class="space-y-6">
                <!-- New Requests Today -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">New Requests Today</h3>
                        <span class="text-sm text-gray-500">Today</span>
                    </div>
                    <p id="new-requests-today" class="text-4xl font-bold text-purple-600 mt-2">{{ $newRequestsToday }}</p>
                    <p class="text-sm text-gray-500 mt-2">Updated in real-time</p>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-700">Pending Requests</h3>
                        <span class="text-sm text-gray-500">For Manager {{ Auth::guard('manager')->user()->manager_number }}</span>
                    </div>
                    <p id="pending-requests" class="text-4xl font-bold text-yellow-500 mt-2">{{ $pendingRequests }}</p>
                </div>
            </div>

            <!-- Right Column: Recent Activity -->
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Recent Activity</h3>
                    <span class="text-sm text-gray-500">Latest</span>
                </div>
                @if($recentActivities->isEmpty())
                    <p class="text-gray-500 text-center py-4">No recent activity to display.</p>
                @else
                    <!-- Scrollable Activity List -->
                    <div id="recent-activities-container" class="overflow-y-auto" style="max-height: 400px;">
                        <ul id="recent-activities-list" class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700">{{ $activity->description }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-600 rounded-full">
                                        {{ ucfirst($activity->type) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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

        // Subscribe to the activities channel
        var activitiesChannel = pusher.subscribe('activities-channel');
        activitiesChannel.bind('new-activity', function(data) {
            let activity = data.activity;

            // Append the new activity to the list
            let activityList = document.getElementById('recent-activities-list');
            let newActivityItem = `
                <li class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">${activity.description}</p>
                        <p class="text-xs text-gray-500 mt-1">Just now</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-purple-100 text-purple-600 rounded-full">
                        ${activity.type}
                    </span>
                </li>
            `;
            activityList.insertAdjacentHTML('afterbegin', newActivityItem);

            // If the "No recent activity" message is visible, hide it
            let noActivityMessage = document.querySelector('.text-gray-500');
            if (noActivityMessage) {
                noActivityMessage.remove();
            }
        });
    </script>
@endsection