@extends('layouts.manager')

@section('content')
<div class="container mx-auto p-4">
<!-- Success Alert -->
@if(session('success'))
<div id="success-alert" 
     class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 dark:bg-green-800 dark:border-green-500 dark:text-green-300" 
     role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="closeAlert()">
        <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-300" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <title>Close</title>
            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
        </svg>
    </span>
</div>
@endif

    <!-- Header for Request List -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-300">Pre Approval List</h2>

        <!-- Search and Date Filter -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="flex items-center space-x-2">
                <input type="date" id="start-date" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <span class="text-gray-500 dark:text-gray-400">to</span>
                <input type="date" id="end-date" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <button id="apply-date-filter" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Filter</button>
                <button id="clear-date-filter" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Clear</button>
            </div>
        </div>
    </div>

    <!-- Scrollable Table Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden flex justify-center dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
            <thead class="bg-gray-800 dark:bg-gray-700">
                <tr>
                    <th class="py-2 px-3 text-sm font-semibold text-white">No.</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Unique Code</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Part Number</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Process Type</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Progress</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Created</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Status</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
                @foreach($requests as $index => $request)
                <tr id="request-row-{{ $request->unique_code }}" 
                    class="hover:bg-gray-300 transition-colors dark:hover:bg-gray-700">
                    
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ $requests->firstItem() + $index }}
                    </td>
                    
                    <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                        <a href="{{ route('manager.request.details', ['unique_code' => $request->unique_code, 'page' => request()->query('page', 1)]) }}">
                            {{ $request->unique_code }}
                        </a>
                    </td>
                    
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $request->part_number }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $request->process_type }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $request->current_process_index }}/{{ $request->total_processes }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ $request->created_at->format('M j, Y, g:i A') }}
                    </td>
                    
                    <td class="py-2 px-3 text-sm text-center">
                        @php
                        $managerNumber = Auth::guard('manager')->user()->manager_number;
                        $status = $request->{"manager_{$managerNumber}_status"};
                        @endphp
                        @if($status === 'approved')
                        <span class="text-green-500 text-xl">✔️</span>
                        @elseif($status === 'rejected')
                        <span class="text-red-500 text-xl">❌</span>
                        @else
                        <span class="text-gray-500 text-xl">⏳</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $requests->appends(request()->except('page'))->links() }}
    </div>
</div>

    <!-- Pusher Script -->
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
        channel.bind("new-request", function (data) {
            let request = data.request;

            // Format the created_at date
            let createdAt = new Date(request.created_at).toLocaleString("en-US", {
                year: "numeric",
                month: "short",
                day: "numeric",
                hour: "numeric",
                minute: "numeric",
                hour12: true
            });

            // Add the new request to the table
            let newRow = `
    <tr id="request-row-${request.unique_code}" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300"></td> <!-- Empty, will be updated -->
        <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
            <a href="/manager/request/details/${request.unique_code}" 
               class="dark:text-blue-400 hover:dark:text-blue-300">
                ${request.unique_code}
            </a>
        </td>
        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${request.part_number || "N/A"}</td>
        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${request.process_type || "N/A"}</td>
        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
            ${request.current_process_index}/${request.total_processes}
        </td>
        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${createdAt}</td>
        <td class="py-2 px-3 text-sm text-center">
            ${getStatusIcon(request[`manager_${managerNumber}_status`])}
        </td>
    </tr>
`;


            document.querySelector("#requests-table-body").innerHTML += newRow;
            updateRowNumbers(); // Update numbering after adding a new row
        });

        // Listen for status updates
        channel.bind("status-updated", function (data) {
            let request = data.request;

            // Find the row in the table that matches the updated request
            let row = document.querySelector(`#request-row-${request.unique_code}`);

            if (row) {
                // Update the status icon for the specific manager
                let managerNumber = {{ Auth::guard('manager')->user()->manager_number }};
                let status = request[`manager_${managerNumber}_status`];
                row.querySelector("td:nth-child(8)").innerHTML = getStatusIcon(status); // 8th column = Manager Status
            }
        });

        // Function to get the status icon based on the status
        function getStatusIcon(status) {
            if (status === 'approved') {
                return '<span class="text-green-500 text-xl">✔️</span>';
            } else if (status === 'rejected') {
                return '<span class="text-red-500 text-xl">❌</span>';
            } else {
                return '<span class="text-gray-500 text-xl">⏳</span>';
            }
        }

        // Call updateRowNumbers() when filtering the table (search or date filters)
        document.getElementById("search-bar").addEventListener("input", function () {
            let searchTerm = this.value.toLowerCase();
            let rows = document.querySelectorAll("#requests-table-body tr");

            rows.forEach((row) => {
                let partNumber = row.querySelector("td:nth-child(3)").textContent.toLowerCase();
                if (partNumber.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            updateRowNumbers(); // Recalculate row numbers after filtering
        });

        // Function to filter rows by date range
        function filterByDateRange(startDate, endDate) {
            let rows = document.querySelectorAll('#requests-table-body tr');

            rows.forEach(row => {
                let dateCell = row.querySelector('td:nth-child(7)').textContent.trim(); // 7th column = Created Date
                let requestDate = new Date(dateCell);

                // Check if the request date is within the selected range
                if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
   // Auto-close after 5 seconds (5000ms)
   setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500); // Remove it after fade out
        }
    }, 5000);

    // Close manually when clicked
    function closeAlert() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }

        // Function to update row numbers dynamically
        function updateRowNumbers() {
            let rows = document.querySelectorAll("#requests-table-body tr");
            rows.forEach((row, index) => {
                row.querySelector("td:first-child").textContent = index + 1; // Update "No." column
            });
        }

        // Apply date filter
        document.getElementById("apply-date-filter").addEventListener("click", function () {
            let startDateInput = document.getElementById("start-date").value;
            let endDateInput = document.getElementById("end-date").value;

            let startDate = startDateInput ? new Date(startDateInput) : null;
            let endDate = endDateInput ? new Date(endDateInput) : null;

            let rows = document.querySelectorAll("#requests-table-body tr");

            rows.forEach((row) => {
                let dateCell = row.querySelector("td:nth-child(7)").textContent.trim(); // 7th column = Created Date
                let requestDate = new Date(dateCell);

                if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            updateRowNumbers(); // Update numbering after filtering

            
        });

        // Clear date filter
        document.getElementById("clear-date-filter").addEventListener("click", function () {
            document.getElementById("start-date").value = "";
            document.getElementById("end-date").value = "";

            let rows = document.querySelectorAll("#requests-table-body tr");
            rows.forEach((row) => (row.style.display = ""));

            updateRowNumbers(); // Restore correct numbering
        });
    </script>
@endsection