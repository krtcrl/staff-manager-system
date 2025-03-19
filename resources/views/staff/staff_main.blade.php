@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-2"> <!-- Reduced padding -->
    
    <!-- Header for Request List -->
    <div class="mb-1 flex justify-between items-center"> <!-- Reduced margin -->
        <h2 class="text-xl font-semibold text-gray-800">Pre Request List</h2> <!-- Smaller font -->
        
        <!-- Search and Date Filter Container -->
        <div class="flex items-center space-x-2"> <!-- Reduced spacing -->
            
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                    class="pl-8 pr-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <svg class="absolute left-2 top-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Date Filter -->
            <div class="flex items-center space-x-1"> <!-- Reduced spacing -->
                <input type="date" id="start-date" 
                    class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <span class="text-gray-500">to</span>
                <input type="date" id="end-date" 
                    class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button id="apply-date-filter" 
                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition">Apply</button>
                <button id="clear-date-filter" 
                    class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400 transition">Clear</button>
            </div>
        </div>
    </div>

    <!-- Compact Scrollable Table with Hover Effects -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto max-w-full max-h-[600px] mt-2"> 
        <table class="w-full border-collapse border border-gray-300 text-center text-sm">
            <thead class="bg-gray-800"> <!-- Dark background for header -->
                <tr>
                    <th class="py-1 px-2 border bg-gray-800 text-white">No.</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Unique Code</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Part Number</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Description</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Process Type</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Progress</th>
                    <th class="py-1 px-2 border bg-blue-900 text-white">Capacity Planning</th>
                    <th class="py-1 px-2 border bg-blue-900 text-white">Prod. Chief</th>
                    <th class="py-1 px-2 border bg-blue-900 text-white">PE</th>
                    <th class="py-1 px-2 border bg-blue-900 text-white">QAE</th>
                    <th class="py-1 px-2 border bg-gray-800 text-white">Created</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
                @foreach($requests as $index => $request)
                    <tr class="border border-gray-300 transition hover:bg-gray-300 hover:shadow-md">
                        <td class="py-1 px-2 border">{{ $requests->firstItem() + $index }}</td>
                        <td class="py-1 px-2 text-blue-500 hover:underline border">
                            <a href="{{ route('staff.request.details', ['unique_code' => $request->unique_code, 'page' => request()->page]) }}">
                                {{ $request->unique_code }}
                            </a>
                        </td>
                        <td class="py-1 px-2 border">{{ $request->part_number }}</td>
                        <td class="py-1 px-2 border">{{ $request->description }}</td>
                        <td class="py-1 px-2 border">{{ $request->process_type }}</td>
                        <td class="py-1 px-2 border">{{ $request->current_process_index }}/{{ $request->total_processes }}</td>

                        <!-- Manager Status -->
                        <td class="py-1 px-2 text-center border">
                            @if($request->manager_1_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_1_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-1 px-2 text-center border">
                            @if($request->manager_2_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_2_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-1 px-2 text-center border">
                            @if($request->manager_3_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_3_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-1 px-2 text-center border">
                            @if($request->manager_4_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_4_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-1 px-2 border">
                            {{ $request->created_at->format('M j, Y, g:i A') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 text-sm">
        {{ $requests->links() }}
    </div>
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    // Initialize Pusher
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    // Subscribe to the requests channel
    var channel = pusher.subscribe('requests-channel');

    // 🔹 Convert UTC time to local GMT+8 before displaying
    function formatDateTime(dateString) {
        let date = new Date(dateString);
        return date.toLocaleString('en-US', {
            timeZone: 'Asia/Singapore',  // Ensures correct timezone
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        });
    }

    // Listen for new request events
    channel.bind('new-request', function(data) {
        let request = data.request;

        // 🔹 Use formatted created_at
        let createdAt = formatDateTime(request.created_at);

        // Create new row
        let newRow = document.createElement('tr');
        newRow.id = `request-row-${request.unique_code}`;
        newRow.classList.add('border', 'border-gray-300', 'transition', 'hover:bg-gray-100', 'hover:shadow-md');

        newRow.innerHTML = `
            <td class="py-1 px-2 border"></td>
            <td class="py-1 px-2 text-blue-500 hover:underline border">
                <a href="/staff/request/details/${request.unique_code}">
                    ${request.unique_code}
                </a>
            </td>
            <td class="py-1 px-2 border">${request.part_number || 'N/A'}</td>
            <td class="py-1 px-2 border">${request.description || 'N/A'}</td>
            <td class="py-1 px-2 border">${request.process_type}</td>
            <td class="py-1 px-2 border">${request.current_process_index}/${request.total_processes}</td>
            <td class="py-1 px-2 text-center border">${getStatusIcon(request.manager_1_status)}</td>
            <td class="py-1 px-2 text-center border">${getStatusIcon(request.manager_2_status)}</td>
            <td class="py-1 px-2 text-center border">${getStatusIcon(request.manager_3_status)}</td>
            <td class="py-1 px-2 text-center border">${getStatusIcon(request.manager_4_status)}</td>
            <td class="py-1 px-2 border">${createdAt}</td>
        `;

        // Insert at the top
        let tableBody = document.querySelector("#requests-table-body");
        tableBody.insertBefore(newRow, tableBody.firstChild);

        // Update row numbers dynamically
        updateRowNumbers();
    });

    // Listen for status updates
    channel.bind('status-updated', function(data) {
        let request = data.request;
        let row = document.querySelector(`#request-row-${request.unique_code}`);

        if (row) {
            // Update status icons
            row.querySelector('.manager-1-status').innerHTML = getStatusIcon(request.manager_1_status);
            row.querySelector('.manager-2-status').innerHTML = getStatusIcon(request.manager_2_status);
            row.querySelector('.manager-3-status').innerHTML = getStatusIcon(request.manager_3_status);
            row.querySelector('.manager-4-status').innerHTML = getStatusIcon(request.manager_4_status);

            // Update progress and process type
            row.querySelector('.progress-column').innerText = `${request.current_process_index}/${request.total_processes}`;
            row.querySelector('td:nth-child(5)').innerText = request.process_type; // Process Type Column
        }
    });

    // Function to get the status icon based on the status
    function getStatusIcon(status) {
        if (status === 'approved') {
            return '<span class="text-green-500">✔️</span>';
        } else if (status === 'rejected') {
            return '<span class="text-red-500">❌</span>';
        } else {
            return '<span class="text-gray-500">⏳</span>';
        }
    }

    // Search Functionality
    document.getElementById('search-bar').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        let rows = document.querySelectorAll('#requests-table-body tr');

        rows.forEach(row => {
            let partNumber = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Fix index to match part number
            if (partNumber.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateRowNumbers();
    });

    // Function to filter rows by date range
    function filterByDateRange(startDate, endDate) {
        let rows = document.querySelectorAll('#requests-table-body tr');

        rows.forEach(row => {
            let dateCell = row.querySelector('td:nth-child(11)').textContent.trim(); // Fix index to match 'Created' column
            let requestDate = new Date(dateCell);

            // Check if the request date is within the selected range
            if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateRowNumbers();
    }

    // Event listener for the Apply Date Filter button
    document.getElementById('apply-date-filter').addEventListener('click', function() {
        let startDateInput = document.getElementById('start-date').value;
        let endDateInput = document.getElementById('end-date').value;

        // Convert the input values to Date objects
        let startDate = startDateInput ? new Date(startDateInput) : null;
        let endDate = endDateInput ? new Date(endDateInput) : null;

        // Filter the table rows
        filterByDateRange(startDate, endDate);
    });

    // Event listener for clearing the date filter
    document.getElementById('clear-date-filter').addEventListener('click', function() {
        // Reset the date inputs
        document.getElementById('start-date').value = '';
        document.getElementById('end-date').value = '';

        // Show all rows
        let rows = document.querySelectorAll('#requests-table-body tr');
        rows.forEach(row => row.style.display = '');

        updateRowNumbers();
    });

    // Function to update row numbers dynamically
    function updateRowNumbers() {
        let rows = document.querySelectorAll("#requests-table-body tr");
        let startNumber = parseInt("{{ $requests->firstItem() }}"); // Get first item number of current page
        let count = startNumber;

        rows.forEach((row) => {
            if (row.style.display !== "none") { // Only count visible rows
                row.querySelector("td:first-child").textContent = count;
                count++;
            }
        });
    }
</script>
@endsection