@extends('layouts.staff')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Header for Request List -->
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Request List</h2>
            
            <!-- Search and Date Filter Container -->
            <div class="flex items-center space-x-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" id="search-bar" placeholder="Search by Part Number" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Date Filter -->
                <div class="flex items-center space-x-2">
                    <input type="date" id="start-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">to</span>
                    <input type="date" id="end-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button id="apply-date-filter" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Apply</button>
                    <button id="clear-date-filter" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Clear</button>
                </div>
            </div>
        </div>

        <!-- Scrollable Table Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex justify-center">
            <table class="min-w-full divide-y divide-gray-200 text-center">
            <thead>
    <tr>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">No.</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Unique Code</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Part Number</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Description</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Manager 1</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Manager 2</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Manager 3</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Manager 4</th>
        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Created</th>
    </tr>
</thead>
<tbody id="requests-table-body">
    @foreach($requests as $index => $request)
        <tr id="request-row-{{ $request->unique_code }}" class="hover:bg-gray-300 transition-colors">
        <td class="py-2 px-3 text-sm text-gray-700">{{ $requests->firstItem() + $index }}</td>
        <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                <a href="{{ route('staff.request.details', ['unique_code' => $request->unique_code, 'page' => request()->page]) }}">
                    {{ $request->unique_code }}
                </a>
            </td>
            <td class="py-2 px-3 text-sm text-gray-700">{{ $request->part_number }}</td>
            <td class="py-2 px-3 text-sm text-gray-700">{{ $request->description }}</td>
            <td class="py-2 px-3 text-sm text-center manager-1-status">
                @if($request->manager_1_status === 'approved')
                    <span class="text-green-500">✔️</span>
                @elseif($request->manager_1_status === 'rejected')
                    <span class="text-red-500">❌</span>
                @else
                    <span class="text-gray-500">⏳</span>
                @endif
            </td>
            <td class="py-2 px-3 text-sm text-center manager-2-status">
                @if($request->manager_2_status === 'approved')
                    <span class="text-green-500">✔️</span>
                @elseif($request->manager_2_status === 'rejected')
                    <span class="text-red-500">❌</span>
                @else
                    <span class="text-gray-500">⏳</span>
                @endif
            </td>
            <td class="py-2 px-3 text-sm text-center manager-3-status">
                @if($request->manager_3_status === 'approved')
                    <span class="text-green-500">✔️</span>
                @elseif($request->manager_3_status === 'rejected')
                    <span class="text-red-500">❌</span>
                @else
                    <span class="text-gray-500">⏳</span>
                @endif
            </td>
            <td class="py-2 px-3 text-sm text-center manager-4-status">
                @if($request->manager_4_status === 'approved')
                    <span class="text-green-500">✔️</span>
                @elseif($request->manager_4_status === 'rejected')
                    <span class="text-red-500">❌</span>
                @else
                    <span class="text-gray-500">⏳</span>
                @endif
            </td>
            <td class="py-2 px-3 text-sm text-gray-700">
                {{ $request->created_at->format('M j, Y, g:i A') }}
            </td>
        </tr>
    @endforeach
</tbody>>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
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

    // Listen for new request events
    channel.bind('new-request', function(data) {
        let request = data.request;

        // Format the created_at date
        let createdAt = new Date(request.created_at).toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            hour12: true
        });

        // Add the new request to the table
        let newRow = `
            <tr id="request-row-${request.unique_code}" class="hover:bg-gray-50 transition-colors">
                <td class="py-2 px-3 text-sm text-gray-700"></td> <!-- Number will be updated later -->
                <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                    <a href="/staff/request/details/${request.unique_code}">
                        ${request.unique_code}
                    </a>
                </td>
                <td class="py-2 px-3 text-sm text-gray-700">${request.part_number || 'N/A'}</td>
                <td class="py-2 px-3 text-sm text-gray-700">${request.description || 'N/A'}</td>
                <td class="py-2 px-3 text-sm text-center manager-1-status">${getStatusIcon(request.manager_1_status)}</td>
                <td class="py-2 px-3 text-sm text-center manager-2-status">${getStatusIcon(request.manager_2_status)}</td>
                <td class="py-2 px-3 text-sm text-center manager-3-status">${getStatusIcon(request.manager_3_status)}</td>
                <td class="py-2 px-3 text-sm text-center manager-4-status">${getStatusIcon(request.manager_4_status)}</td>
                <td class="py-2 px-3 text-sm text-gray-700">${createdAt}</td>
            </tr>
        `;

        document.querySelector("#requests-table-body").innerHTML += newRow;
        updateRowNumbers();
    });

    // Listen for status updates
    channel.bind('status-updated', function(data) {
        let request = data.request;

        // Find the row in the table that matches the updated request
        let row = document.querySelector(`#request-row-${request.unique_code}`);

        if (row) {
            // Update the status icons for each manager
            row.querySelector('.manager-1-status').innerHTML = getStatusIcon(request.manager_1_status);
            row.querySelector('.manager-2-status').innerHTML = getStatusIcon(request.manager_2_status);
            row.querySelector('.manager-3-status').innerHTML = getStatusIcon(request.manager_3_status);
            row.querySelector('.manager-4-status').innerHTML = getStatusIcon(request.manager_4_status);
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
            let dateCell = row.querySelector('td:nth-child(9)').textContent.trim(); // Fix index to match 'Created' column
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