@extends('layouts.manager')

@section('content')
<!-- Main Container -->
<div class="container mx-auto p-4 md:p-6">
    <!-- Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        
        <!-- Left Column: Welcome Message + Statistics -->
        <div class="space-y-6">
            
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs transition-all duration-300 hover:shadow-sm">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-slate-100">
                            Welcome back, {{ Auth::guard('manager')->user()->name }}!
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400 mt-2">
                            You are logged in as 
                            @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">Pre Approval Manager</span>
                            @elseif(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                                <span class="font-semibold text-sky-600 dark:text-sky-400">Final Approval Manager</span>
                            @endif
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center shadow-inner border border-slate-200 dark:border-slate-600">
                            <span class="text-xl font-bold text-slate-700 dark:text-slate-300">
                                {{ substr(Auth::guard('manager')->user()->name, 0, 1) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Requests Today -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs transition-all duration-300 hover:shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">New Requests Today</h3>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 font-medium border border-slate-200 dark:border-slate-600">Today</span>
                </div>
                <div class="flex items-end mt-2">
                    <p id="new-requests-today" class="text-4xl font-bold text-slate-800 dark:text-slate-200">
                        {{ $newRequestsToday }}
                    </p>
                    <span class="text-sm text-slate-500 dark:text-slate-400 ml-2 mb-1">requests</span>
                </div>
                <div class="mt-4 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden border border-slate-200 dark:border-slate-600">
                    <div class="h-full bg-gradient-to-r from-indigo-400 to-sky-400" style="width: {{ min(($newRequestsToday / 20) * 100, 100) }}%"></div>
                </div>
            </div>

            <!-- Approval Cards Container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Pending Pre-Approval Requests -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 2, 3, 4]))
                    <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs transition-all duration-300 hover:shadow-sm group">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-indigo-50/80 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 mr-3 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition-colors border border-indigo-100 dark:border-indigo-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Initial Reviews</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Awaiting your evaluation</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 font-medium border border-indigo-200 dark:border-indigo-800">
                                M{{ Auth::guard('manager')->user()->manager_number }}
                            </span>
                        </div>
                        <div class="flex items-end mt-4">
                            <p id="pending-requests" class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $pendingRequests }}
                            </p>
                            <span class="text-sm text-slate-500 dark:text-slate-400 ml-2 mb-1">pending</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('manager.request-list') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors flex items-center">
                                Review requests
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Pending Final Requests -->
                @if(in_array(Auth::guard('manager')->user()->manager_number, [1, 5, 6, 7, 8, 9]))
                    <div class="bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs transition-all duration-300 hover:shadow-sm group">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg bg-sky-50/80 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 mr-3 group-hover:bg-sky-100 dark:group-hover:bg-sky-900/30 transition-colors border border-sky-100 dark:border-sky-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Final Approvals</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Ready for your decision</p>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-sky-100 text-sky-800 dark:bg-sky-900 dark:text-sky-200 font-medium border border-sky-200 dark:border-sky-800">
                                M{{ Auth::guard('manager')->user()->manager_number }}
                            </span>
                        </div>
                        <div class="flex items-end mt-4">
                            <p id="pending-final-requests" class="text-4xl font-bold text-sky-600 dark:text-sky-400">
                                {{ $pendingFinalRequests }}
                            </p>
                            <span class="text-sm text-slate-500 dark:text-slate-400 ml-2 mb-1">awaiting</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('manager.finalrequest-list') }}" class="text-sm font-medium text-sky-600 dark:text-sky-400 hover:text-sky-800 dark:hover:text-sky-300 transition-colors flex items-center">
                                Process approvals
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Recent Activity -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs transition-all duration-300 hover:shadow-sm flex flex-col h-full">
            <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Recent Activity
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 font-medium border border-slate-200 dark:border-slate-600">Live</span>
                        <button id="refresh-activities" class="p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" title="Refresh">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
                            $badgeColor = "bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200";
                            $iconColor = "text-slate-500";
                            $icon = "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z";
                            
                            if (strtolower($activity->type) === "rejection" || str_contains(strtolower($activity->type), "reject")) {
                                $badgeColor = "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
                                $iconColor = "text-red-500";
                                $icon = "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z";
                            } elseif (strtolower($activity->type) === "approval" || str_contains(strtolower($activity->type), "approve")) {
                                $badgeColor = "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
                                $iconColor = "text-green-500";
                                $icon = "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z";
                            }
                        @endphp
                        <li class="flex items-start p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors duration-200 border border-slate-200 dark:border-slate-600"
                            data-timestamp="{{ $activity->created_at->timestamp }}">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $iconColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0 ml-3">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                    {{ $activity->description }}
                                </p>
                                <div class="flex items-center mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $activity->created_at->diffForHumans() }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="font-medium">{{ ucfirst($activity->request_type) }}</span>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $badgeColor }} ml-2 flex-shrink-0 self-center border border-slate-200 dark:border-slate-600">
                                {{ ucfirst($activity->type) }}
                            </span>
                        </li>
                    @endforeach
                    
                    @if(count($recentActivities) === 0)
                        <li class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-3 text-slate-500 dark:text-slate-400">No recent activity found</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Activities will appear here as they occur</p>
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
            const element = document.getElementById('new-requests-today');
            if (element) {
                element.innerText = data.newRequestsToday;
                element.classList.add('animate-bounce');
                setTimeout(() => {
                    element.classList.remove('animate-bounce');
                }, 1000);
            }
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
            let iconColor = "text-gray-500";
            let icon = "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z";
            
            if (type === "rejection" || type.includes("reject")) {
                badgeColor = "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200";
                iconColor = "text-red-500";
                icon = "M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z";
            } else if (type === "approval" || type.includes("approve")) {
                badgeColor = "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200";
                iconColor = "text-green-500";
                icon = "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z";
            }

            const newActivityItem = document.createElement('li');
            newActivityItem.className = "flex items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 animate-fade-in";
            newActivityItem.setAttribute('data-timestamp', Math.floor(Date.now() / 1000));
            
            newActivityItem.innerHTML = `
                <div class="flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="${icon}" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0 ml-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        ${activity.description}
                    </p>
                    <div class="flex items-center mt-1 text-xs text-gray-500 dark:text-gray-400">
                        <span>Just now</span>
                        <span class="mx-2">•</span>
                        <span class="font-medium">${activity.request_type}</span>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium ${badgeColor} ml-2 flex-shrink-0 self-center">
                    ${activity.type}
                </span>
            `;

            const activityList = document.getElementById('recent-activities-list');
            
            // Remove "No activities" message if it exists
            if (activityList.children.length === 1 && activityList.children[0].querySelector('svg')) {
                activityList.innerHTML = '';
            }
            
            activityList.prepend(newActivityItem);
            
            // Remove animation class after it completes
            setTimeout(() => {
                newActivityItem.classList.remove('animate-fade-in');
            }, 500);
            
            // Clean up old activities (older than 5 days)
            const fiveDaysAgo = Date.now() - (5 * 24 * 60 * 60 * 1000);
            document.querySelectorAll('#recent-activities-list li').forEach(item => {
                const timestamp = parseInt(item.getAttribute('data-timestamp')) * 1000;
                if (timestamp < fiveDaysAgo) {
                    item.classList.add('animate-fade-out');
                    setTimeout(() => item.remove(), 500);
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
    });
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-8px); }
    }
</style>
@endsection