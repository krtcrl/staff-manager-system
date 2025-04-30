@extends('layouts.manager')

@section('content')
@php
// Define manager-specific status icons with tooltips
function getStatusBadge($status, $managerTitle = '') {
    $icon = '';
    $text = '';
    $bgColor = '';
    $textColor = '';
    $darkBgColor = '';
    $darkTextColor = '';
    
    if ($status === 'approved') {
        $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        $text = 'Approved';
        $bgColor = 'bg-green-100';
        $textColor = 'text-green-800';
        $darkBgColor = 'dark:bg-green-900';
        $darkTextColor = 'dark:text-green-200';
    } elseif ($status === 'rejected') {
        $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        $text = 'Rejected';
        $bgColor = 'bg-red-100';
        $textColor = 'text-red-800';
        $darkBgColor = 'dark:bg-red-900';
        $darkTextColor = 'dark:text-red-200';
    } else {
        $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        $text = 'Pending';
        $bgColor = 'bg-yellow-100';
        $textColor = 'text-yellow-800';
        $darkBgColor = 'dark:bg-yellow-900';
        $darkTextColor = 'dark:text-yellow-200';
    }
    
    $title = $managerTitle ? "title=\"{$managerTitle}: {$status}\"" : '';
    
    return "<span class=\"inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {$bgColor} {$textColor} {$darkBgColor} {$darkTextColor}\" {$title}>
        {$icon}{$text}
    </span>";
}

$manager = Auth::guard('manager')->user();
$managerNumber = $manager ? $manager->manager_number : null;
$managerTitle = $manager ? $manager->title : '';

// Map manager numbers to their corresponding table column names
$managerColumnMap = [
    1 => ['column' => 'manager_1_status', 'title' => 'Capacity Planning'],
    5 => ['column' => 'manager_2_status', 'title' => 'Planning'],
    6 => ['column' => 'manager_3_status', 'title' => 'Product'],
    7 => ['column' => 'manager_4_status', 'title' => 'EE'],
    8 => ['column' => 'manager_5_status', 'title' => 'QAE'],
    9 => ['column' => 'manager_6_status', 'title' => 'General Manager']
];

$statusColumn = $managerNumber && isset($managerColumnMap[$managerNumber]) 
    ? $managerColumnMap[$managerNumber]['column'] 
    : null;
$managerTitle = $managerNumber && isset($managerColumnMap[$managerNumber])
    ? $managerColumnMap[$managerNumber]['title']
    : 'Manager';
@endphp

<!-- Success Alert with Animation -->
@if(session('success'))
<div id="success-alert" 
     class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 transition-all duration-300 ease-in-out dark:bg-green-800/50 dark:border-green-600 dark:text-green-200 flex justify-between items-center">
    <span class="flex-grow">{{ session('success') }}</span>
    <button onclick="closeAlert()" class="ml-4 text-green-700 hover:text-green-900 dark:text-green-200 dark:hover:text-green-300">
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </button>
</div>
@endif

<div class="container mx-auto p-4">
    <!-- Header with Manager Info and Controls -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Final Approval List</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $managerTitle }}: {{ Auth::guard('manager')->user()->name }}
            </p>
        </div>

        <!-- Search and Filter Controls -->
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <!-- Search with Clear Button -->
            <div class="relative flex-grow">
                <input type="text" id="search-bar" placeholder="Search by Part Number or Code" 
                    class="w-full pl-10 pr-8 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button id="clear-search" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hidden">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Date Range Filter -->
            <div class="flex flex-col sm:flex-row items-stretch gap-2">
                <div class="flex items-center gap-2">
                    <input type="date" id="start-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                    <span class="text-gray-500 dark:text-gray-400 whitespace-nowrap">to</span>
                    <input type="date" id="end-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                </div>
                <div class="flex gap-2">
                    <button id="apply-date-filter" 
                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors flex-grow">
                        Filter
                    </button>
                    <button id="clear-date-filter" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Requests</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $totalRequests }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50">
                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Approved</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $approvedRequests }}</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/50">
                    <svg class="w-6 h-6 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $rejectedRequests }}</p>
                </div>
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50">
                    <svg class="w-6 h-6 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $pendingRequests }}</p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/50">
                    <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-800">
        @if($finalRequests->isEmpty())
            <!-- Empty State -->
            <div class="p-8 text-center w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-300">No pending final approvals</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All caught up! New requests will appear here.</p>
            </div>
        @else
            <!-- Scrollable Table Container -->
            <div class="overflow-x-auto">
                <div class="max-h-[calc(100vh-300px)] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Request
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Part Number
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Part Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Created
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($finalRequests as $index => $request)
                            @php
                                $status = $statusColumn ? $request->{$statusColumn} : 'pending';
                                $createdAtGMT8 = $request->created_at
                                    ->setTimezone('Asia/Manila')
                                    ->format('M j, Y, g:i A');
                            @endphp
                            <tr id="finalrequest-row-{{ $request->unique_code }}" 
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                data-part-number="{{ strtolower($request->part_number) }}"
                                data-unique-code="{{ strtolower($request->unique_code) }}"
                                data-created-at="{{ $request->created_at->timestamp }}"
                                data-status="{{ $status }}">
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                <a href="{{ route('manager.finalrequest.details', ['unique_code' => $request->unique_code]) }}">
                                                    {{ $request->unique_code }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $request->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $request->part_number }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $request->part_name }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $createdAtGMT8 }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {!! getStatusBadge($status, $managerTitle) !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Pusher Script -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const managerNumber = {{ Auth::guard('manager')->user()->manager_number }};
        const managerTitle = "{{ $managerTitle }}";
        
        // Initialize Pusher
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        // Subscribe to the finalrequests channel
        const channel = pusher.subscribe('finalrequests-channel');

        // Format date time with timezone
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                timeZone: 'Asia/Manila',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });
        }

        // Get status badge HTML
        function getStatusBadge(status, title = '') {
            let icon = '';
            let text = '';
            let bgColor = '';
            let textColor = '';
            let darkBgColor = '';
            let darkTextColor = '';
            
            if (status === 'approved') {
                icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                text = 'Approved';
                bgColor = 'bg-green-100';
                textColor = 'text-green-800';
                darkBgColor = 'dark:bg-green-900';
                darkTextColor = 'dark:text-green-200';
            } else if (status === 'rejected') {
                icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                text = 'Rejected';
                bgColor = 'bg-red-100';
                textColor = 'text-red-800';
                darkBgColor = 'dark:bg-red-900';
                darkTextColor = 'dark:text-red-200';
            } else {
                icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                text = 'Pending';
                bgColor = 'bg-yellow-100';
                textColor = 'text-yellow-800';
                darkBgColor = 'dark:bg-yellow-900';
                darkTextColor = 'dark:text-yellow-200';
            }
            
            const titleAttr = title ? `title="${title}: ${status}"` : '';
            
            return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${bgColor} ${textColor} ${darkBgColor} ${darkTextColor}" ${titleAttr}>
                ${icon}${text}
            </span>`;
        }

        // Handle new final request events
        channel.bind("new-finalrequest", function(data) {
            const request = data.finalRequest;
            const createdAt = formatDateTime(request.created_at);
            const status = request[`manager_${managerNumber}_status`] || 'pending';
            
            // Create new row
            const newRow = document.createElement('tr');
            newRow.id = `finalrequest-row-${request.unique_code}`;
            newRow.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors';
            newRow.dataset.partNumber = request.part_number.toLowerCase();
            newRow.dataset.uniqueCode = request.unique_code.toLowerCase();
            newRow.dataset.createdAt = new Date(request.created_at).getTime();
            newRow.dataset.status = status;
            
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"></td>
                
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                <a href="/manager/finalrequest/details/${request.unique_code}">
                                    ${request.unique_code}
                                </a>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                ${timeSince(new Date(request.created_at))}
                            </div>
                        </div>
                    </div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">${request.part_number || 'N/A'}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">${request.part_name || 'N/A'}</div>
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${createdAt}
                </td>
                
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    ${getStatusBadge(status, managerTitle)}
                </td>
            `;

            const tableBody = document.querySelector("tbody");
            if (tableBody) {
                // Remove empty state if present
                const emptyRow = tableBody.querySelector('tr[colspan]');
                if (emptyRow) {
                    tableBody.removeChild(emptyRow);
                }
                
                tableBody.insertBefore(newRow, tableBody.firstChild);
                updateRowNumbers();
            }
        });

        // Handle status updates
        channel.bind("status-updated", function(data) {
            const request = data.finalRequest;
            const row = document.querySelector(`#finalrequest-row-${request.unique_code}`);
            
            if (row) {
                const status = request[`manager_${managerNumber}_status`] || 'pending';
                
                // Update the status badge
                row.querySelector('td:nth-child(6)').innerHTML = getStatusBadge(status, managerTitle);
                
                // Update the data attribute
                row.dataset.status = status;
            }
        });

        // Calculate time since
        function timeSince(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) return `${interval} year${interval === 1 ? '' : 's'} ago`;
            
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) return `${interval} month${interval === 1 ? '' : 's'} ago`;
            
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) return `${interval} day${interval === 1 ? '' : 's'} ago`;
            
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) return `${interval} hour${interval === 1 ? '' : 's'} ago`;
            
            interval = Math.floor(seconds / 60);
            if (interval >= 1) return `${interval} minute${interval === 1 ? '' : 's'} ago`;
            
            return `${Math.floor(seconds)} second${seconds === 1 ? '' : 's'} ago`;
        }

        // Search functionality
        const searchBar = document.getElementById('search-bar');
        const clearSearchBtn = document.getElementById('clear-search');
        
        searchBar.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
            
            filterTable();
        });
        
        clearSearchBtn.addEventListener('click', function() {
            searchBar.value = '';
            clearSearchBtn.classList.add('hidden');
            filterTable();
        });
        
        // Date filter functionality
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyDateFilterBtn = document.getElementById('apply-date-filter');
        const clearDateFilterBtn = document.getElementById('clear-date-filter');
        
        applyDateFilterBtn.addEventListener('click', filterTable);
        clearDateFilterBtn.addEventListener('click', function() {
            startDateInput.value = '';
            endDateInput.value = '';
            filterTable();
        });
        
        // Combined filter function
        function filterTable() {
            const searchTerm = searchBar.value.toLowerCase().trim();
            const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
            const endDate = endDateInput.value ? new Date(endDateInput.value) : null;
            
            document.querySelectorAll('tbody tr').forEach(row => {
                const partNumber = row.dataset.partNumber || '';
                const uniqueCode = row.dataset.uniqueCode || '';
                const createdAt = parseInt(row.dataset.createdAt) || 0;
                const status = row.dataset.status || '';
                
                // Search filter
                const matchesSearch = !searchTerm || 
                    partNumber.includes(searchTerm) || 
                    uniqueCode.includes(searchTerm);
                
                // Date filter
                const matchesDate = (!startDate || new Date(createdAt) >= startDate) && 
                                  (!endDate || new Date(createdAt) <= endDate);
                
                // Show/hide based on filters
                row.style.display = matchesSearch && matchesDate ? '' : 'none';
            });
            
            updateRowNumbers();
        }
        
        // Function to update row numbers dynamically
        function updateRowNumbers() {
            const visibleRows = Array.from(document.querySelectorAll('tbody tr:not([style*="display: none"])'));
            
            visibleRows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        // Close alert manually
        function closeAlert() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }

        // Auto-close alert after 5 seconds
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);

        // Initialize row numbers
        updateRowNumbers();
    });
</script>
@endsection