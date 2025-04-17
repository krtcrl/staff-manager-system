@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-4">

    <!-- Header with improved layout and responsive design -->
    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-300">Pre Approval List</h2>

        <!-- Search and Date Filter with better responsive behavior -->
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center gap-2">
            <!-- Search Bar with clear button -->
            <div class="relative flex-grow">
                <input type="text" id="search-bar" placeholder="Search by Part Number or Unique Code" 
                    class="w-full pl-8 pr-8 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                <svg class="absolute left-2 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <button id="clear-search" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Date Filter with improved layout -->
            <div class="flex flex-col sm:flex-row items-stretch gap-2">
                <div class="flex items-center gap-1">
                    <input type="date" id="start-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                    <span class="text-gray-500 dark:text-gray-400 whitespace-nowrap">to</span>
                    <input type="date" id="end-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                </div>
                <div class="flex gap-2">
                    <button id="apply-date-filter" 
                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition flex-grow">Filter</button>
                    <button id="clear-date-filter" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400 transition">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table with improved accessibility and loading state -->
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Loading overlay -->
        <div id="loading-overlay" class="absolute inset-0 bg-gray-100 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-50 flex items-center justify-center z-10 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
        
        <!-- Table container with sticky headers -->
        <div class="overflow-x-auto max-h-[calc(100vh-200px)]">
            <table class="w-full border-collapse" aria-label="Pre-approval requests list">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-gray-800 dark:bg-gray-700 text-white">
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">#</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Unique Code</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Part Number</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Process Type</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-center">Progress</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-center bg-blue-900">Capacity Planning</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-center bg-blue-900">Prod. Chief</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-center bg-blue-900">PE</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-center bg-blue-900">QAE</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Created</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @if($requests->isEmpty())
                        <tr>
                            <td colspan="10" class="py-8 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-300">No requests for pre-approval at the moment.</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">When new requests arrive, they'll appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($requests as $index => $request)
                            <tr id="request-row-{{ $request->unique_code }}" 
                                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                data-part-number="{{ strtolower($request->part_number) }}"
                                data-unique-code="{{ strtolower($request->unique_code) }}"
                                data-created-at="{{ $request->created_at->timestamp }}">
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $requests->firstItem() + $index }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('staff.request.details', ['unique_code' => $request->unique_code, 'page' => request()->page]) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $request->unique_code }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $request->part_number }}</td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($request->process_type === 'new') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($request->process_type === 'change') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300 @endif">
                                        {{ ucfirst($request->process_type) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $request->current_process_index }}/{{ $request->total_processes }}
                                        </span>
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-1">
                                            <div class="bg-blue-500 h-2 rounded-full" 
                                                 style="width: {{ ($request->current_process_index / $request->total_processes) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Manager Status with tooltips -->
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block" title="Capacity Planning: {{ $request->manager_1_status ?? 'pending' }}">
                                        @if($request->manager_1_status === 'approved')
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($request->manager_1_status === 'rejected')
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block" title="Production Chief: {{ $request->manager_2_status ?? 'pending' }}">
                                        @if($request->manager_2_status === 'approved')
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($request->manager_2_status === 'rejected')
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block" title="PE: {{ $request->manager_3_status ?? 'pending' }}">
                                        @if($request->manager_3_status === 'approved')
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($request->manager_3_status === 'rejected')
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="inline-block" title="QAE: {{ $request->manager_4_status ?? 'pending' }}">
                                        @if($request->manager_4_status === 'approved')
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($request->manager_4_status === 'rejected')
                                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $request->created_at->format('M j, Y, g:i A') }}
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $request->created_at->diffForHumans() }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination with improved styling -->
    @unless($requests->isEmpty())
    <div class="mt-4">
        {{ $requests->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
    @endunless
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Pusher
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        // Subscribe to the requests channel
        const channel = pusher.subscribe('requests-channel');
        
        // Format date time with timezone
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                timeZone: 'Asia/Singapore',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            });
        }

        // Show loading indicator
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading indicator
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Update row numbers based on visible rows
        function updateRowNumbers() {
            const visibleRows = Array.from(document.querySelectorAll('#requests-table-body tr:not([style*="display: none"])'));
            const startNumber = parseInt("{{ $requests->firstItem() }}");
            
            visibleRows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = startNumber + index;
            });
        }

        // Get status icon HTML
        function getStatusIcon(status) {
            if (status === 'approved') {
                return '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            } else if (status === 'rejected') {
                return '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
            } else {
                return '<svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>';
            }
        }

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

        // Handle new request events
        channel.bind('new-request', function(data) {
            const request = data.request;
            const createdAt = formatDateTime(request.created_at);
            
            const newRow = document.createElement('tr');
            newRow.id = `request-row-${request.unique_code}`;
            newRow.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150';
            newRow.dataset.partNumber = request.part_number.toLowerCase();
            newRow.dataset.uniqueCode = request.unique_code.toLowerCase();
            newRow.dataset.createdAt = new Date(request.created_at).getTime();
            
            newRow.innerHTML = `
                <td class="py-3 px-4"></td>
                <td class="py-3 px-4">
                    <a href="/staff/request/details/${request.unique_code}"
                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        ${request.unique_code}
                    </a>
                </td>
                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">${request.part_number || 'N/A'}</td>
                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                        ${request.process_type === 'new' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                          request.process_type === 'change' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                          'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300'}">
                        ${request.process_type ? request.process_type.charAt(0).toUpperCase() + request.process_type.slice(1) : 'N/A'}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <div class="flex flex-col items-center">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            ${request.current_process_index}/${request.total_processes}
                        </span>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-1">
                            <div class="bg-blue-500 h-2 rounded-full" 
                                 style="width: ${(request.current_process_index / request.total_processes) * 100}%"></div>
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-block" title="Capacity Planning: ${request.manager_1_status || 'pending'}">
                        ${getStatusIcon(request.manager_1_status)}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-block" title="Production Chief: ${request.manager_2_status || 'pending'}">
                        ${getStatusIcon(request.manager_2_status)}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-block" title="PE: ${request.manager_3_status || 'pending'}">
                        ${getStatusIcon(request.manager_3_status)}
                    </span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="inline-block" title="QAE: ${request.manager_4_status || 'pending'}">
                        ${getStatusIcon(request.manager_4_status)}
                    </span>
                </td>
                <td class="py-3 px-4 text-gray-500 dark:text-gray-400 text-sm">
                    ${createdAt}
                    <div class="text-xs text-gray-400 dark:text-gray-500">
                        ${timeSince(new Date(request.created_at))}
                    </div>
                </td>
            `;

            const tableBody = document.querySelector("#requests-table-body");
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

        // Handle status update events
        channel.bind('status-updated', function(data) {
            const request = data.request;
            const row = document.querySelector(`#request-row-${request.unique_code}`);
            
            if (row) {
                // Update status icons
                row.querySelector('td:nth-child(6) span').innerHTML = getStatusIcon(request.manager_1_status);
                row.querySelector('td:nth-child(6) span').title = `Capacity Planning: ${request.manager_1_status || 'pending'}`;
                
                row.querySelector('td:nth-child(7) span').innerHTML = getStatusIcon(request.manager_2_status);
                row.querySelector('td:nth-child(7) span').title = `Production Chief: ${request.manager_2_status || 'pending'}`;
                
                row.querySelector('td:nth-child(8) span').innerHTML = getStatusIcon(request.manager_3_status);
                row.querySelector('td:nth-child(8) span').title = `PE: ${request.manager_3_status || 'pending'}`;
                
                row.querySelector('td:nth-child(9) span').innerHTML = getStatusIcon(request.manager_4_status);
                row.querySelector('td:nth-child(9) span').title = `QAE: ${request.manager_4_status || 'pending'}`;
                
                // Update progress
                const progressCell = row.querySelector('td:nth-child(5)');
                if (progressCell) {
                    progressCell.innerHTML = `
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                ${request.current_process_index}/${request.total_processes}
                            </span>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-1">
                                <div class="bg-blue-500 h-2 rounded-full" 
                                     style="width: ${(request.current_process_index / request.total_processes) * 100}%"></div>
                            </div>
                        </div>
                    `;
                }
            }
        });

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
            
            document.querySelectorAll('#requests-table-body tr').forEach(row => {
                const partNumber = row.dataset.partNumber || '';
                const uniqueCode = row.dataset.uniqueCode || '';
                const createdAt = parseInt(row.dataset.createdAt) || 0;
                
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
        
        // Initialize row numbers
        updateRowNumbers();
    });
</script>
@endsection