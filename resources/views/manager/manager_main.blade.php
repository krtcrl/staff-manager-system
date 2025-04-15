@extends('layouts.manager')

@section('content')
<!-- Main Container -->
<div class="container mx-auto p-4 md:p-6">
    <!-- Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        
        <!-- Left Column: Welcome Message + Statistics -->
        <div class="space-y-6">
            
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-gray-200">
                            Welcome back, {{ Auth::guard('manager')->user()->name }}!
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            You are logged in as 
                            @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                                a <span class="font-semibold text-purple-600 dark:text-purple-400">Pre Approval Manager</span>.
                            @elseif(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                                a <span class="font-semibold text-blue-600 dark:text-blue-400">Final Approval Manager</span>.
                            @endif
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-100 to-blue-100 dark:from-purple-900 dark:to-blue-900 flex items-center justify-center">
                            <span class="text-xl font-bold text-purple-600 dark:text-purple-300">
                                {{ substr(Auth::guard('manager')->user()->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Requests Today -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">New Requests Today</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Updated in real-time</p>
                    </div>
                    <span class="text-sm px-3 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Today</span>
                </div>
                <p id="new-requests-today" class="text-4xl font-bold text-purple-600 dark:text-purple-400 mt-2">
                    {{ $newRequestsToday }}
                </p>
                <div class="mt-4 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-500 dark:bg-purple-400" style="width: {{ min(($newRequestsToday / 20) * 100, 100) }}%"></div>
                </div>
            </div>

            <!-- Two-Column Container: Pending Requests -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Pending Pre-Approval Requests -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pending Pre-Approvals</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Requires your action</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Manager {{ Auth::guard('manager')->user()->manager_number }}
                            </span>
                        </div>
                        <p id="pending-requests" class="text-4xl font-bold text-yellow-500 dark:text-yellow-400 mt-2">
                            {{ $pendingRequests }}
                        </p>
                    </div>
                @endif

                <!-- Pending Final Requests -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pending Final Approvals</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Requires your action</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Manager {{ Auth::guard('manager')->user()->manager_number }}
                            </span>
                        </div>
                        <p id="pending-final-requests" class="text-4xl font-bold text-blue-500 dark:text-blue-400 mt-2">
                            {{ $pendingFinalRequests }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl flex flex-col h-full">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Recent Activity</h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Latest</span>
                        <button id="refresh-activities" class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Scrollable Activity List -->
            <div id="recent-activities-container" class="overflow-y-auto flex-grow p-4" style="max-height: 430px;">
                <ul id="recent-activities-list" class="space-y-3">
                    @foreach($recentActivities as $activity)
                        @php
                            $badgeColor = "bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200";
                            if (strtolower($activity->type) === "rejection" || str_contains(strtolower($activity->type), "reject")) {
                                $badgeColor = "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
                            } elseif (strtolower($activity->type) === "approval" || str_contains(strtolower($activity->type), "approve")) {
                                $badgeColor = "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
                            }
                        @endphp
                        <li class="flex items-start justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200"
                            data-timestamp="{{ $activity->created_at->timestamp }}">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">
                                    {{ $activity->description }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $activity->created_at->diffForHumans() }} | 
                                    <span class="font-semibold">{{ ucfirst($activity->request_type) }}</span>
                                </p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $badgeColor }} ml-2 flex-shrink-0">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </li>
                    @endforeach
                    
                    @if(count($recentActivities) === 0)
                        <li class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">No recent activity found</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Pusher Script for Real-Time Updates -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Pusher
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        // Listen for new requests
        const requestsChannel = pusher.subscribe('requests-channel');
        requestsChannel.bind('new-request', function(data) {
            document.getElementById('new-requests-today').innerText = data.newRequestsToday;
            
            // Animate the update
            const element = document.getElementById('new-requests-today');
            element.classList.add('animate-pulse');
            setTimeout(() => {
                element.classList.remove('animate-pulse');
            }, 1000);
        });

        // Listen for final request status updates
        const finalRequestsChannel = pusher.subscribe('finalrequests-channel');
        finalRequestsChannel.bind('status-updated', function(data) {
            const element = document.getElementById('pending-final-requests');
            if (element) {
                element.innerText = data.pendingFinalRequests;
                element.classList.add('animate-pulse');
                setTimeout(() => {
                    element.classList.remove('animate-pulse');
                }, 1000);
            }
        });

        // Subscribe to Activities Channel
        const activitiesChannel = pusher.subscribe('activities-channel');
        activitiesChannel.bind('new-activity', function(data) {
            const activity = data.activity;
            const type = activity.type.toLowerCase();

            let badgeColor = "bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200";
            if (type === "rejection" || type.includes("reject")) {
                badgeColor = "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
            } else if (type === "approval" || type.includes("approve")) {
                badgeColor = "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
            }

            const newActivityItem = document.createElement('li');
            newActivityItem.className = "flex items-start justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200";
            newActivityItem.setAttribute('data-timestamp', Math.floor(Date.now() / 1000));
            
            newActivityItem.innerHTML = `
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">
                        ${activity.description}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Just now | 
                        <span class="font-semibold">${activity.request_type}</span>
                    </p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium ${badgeColor} ml-2 flex-shrink-0">
                    ${activity.type}
                </span>
            `;

            const activityList = document.getElementById('recent-activities-list');
            
            // Remove "No activities" message if it exists
            if (activityList.children.length === 1 && activityList.children[0].querySelector('svg')) {
                activityList.innerHTML = '';
            }
            
            activityList.prepend(newActivityItem);
            
            // Add animation for new item
            newActivityItem.classList.add('animate-fade-in');
            setTimeout(() => {
                newActivityItem.classList.remove('animate-fade-in');
            }, 500);
            
            // Clean up old activities (older than 5 days)
            const fiveDaysAgo = Date.now() - (5 * 24 * 60 * 60 * 1000);
            document.querySelectorAll('#recent-activities-list li').forEach(item => {
                const timestamp = parseInt(item.getAttribute('data-timestamp')) * 1000;
                if (timestamp < fiveDaysAgo) {
                    item.remove();
                }
            });
        });

        // Refresh activities button
        document.getElementById('refresh-activities')?.addEventListener('click', function() {
            this.classList.add('animate-spin');
            setTimeout(() => {
                this.classList.remove('animate-spin');
            }, 1000);
            
            // Here you would typically fetch fresh data via AJAX
            // For now, we'll just scroll to top
            document.getElementById('recent-activities-container').scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Initial cleanup of old activities
        const fiveDaysAgo = Date.now() - (5 * 24 * 60 * 60 * 1000);
        document.querySelectorAll('#recent-activities-list li').forEach(item => {
            const timestamp = parseInt(item.getAttribute('data-timestamp')) * 1000;
            if (timestamp < fiveDaysAgo) {
                item.remove();
            }
        });
    });
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection