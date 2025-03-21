@extends('layouts.manager')

@section('content')
    <div class="container mx-auto p-4 transition-colors duration-300" :class="{ 'bg-gray-900 text-white': darkMode, 'bg-white text-gray-900': !darkMode }" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">
        <!-- Dark Mode Toggle Button -->
        <div class="flex justify-end mb-4">
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="px-4 py-2 rounded-lg transition-colors duration-300"
                    :class="darkMode ? 'bg-gray-700 text-white' : 'bg-gray-300 text-gray-800'">
                <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
            </button>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
            <div class="px-4 py-3 mb-4 rounded relative"
                :class="darkMode ? 'bg-green-900 text-green-300 border-green-700' : 'bg-green-100 text-green-700 border-green-400'"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header for Request List -->
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold" :class="darkMode ? 'text-gray-300' : 'text-gray-800'">Pre Approval List</h2>

            <!-- Search and Date Filter Container -->
            <div class="flex items-center space-x-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" id="search-bar" placeholder="Search by Part Number"
                        class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none transition-colors"
                        :class="darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'">
                    <svg class="absolute left-3 top-2.5 h-5 w-5" fill="none" stroke="currentColor"
                         :class="darkMode ? 'text-gray-400' : 'text-gray-700'"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Date Filter -->
                <div class="flex items-center space-x-2">
                    <input type="date" id="start-date" class="px-4 py-2 border rounded-lg focus:outline-none"
                        :class="darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'">
                    <span :class="darkMode ? 'text-gray-400' : 'text-gray-500'">to</span>
                    <input type="date" id="end-date" class="px-4 py-2 border rounded-lg focus:outline-none"
                        :class="darkMode ? 'bg-gray-700 text-white border-gray-600' : 'bg-white text-gray-900 border-gray-300'">
                    <button id="apply-date-filter" class="px-4 py-2 rounded-lg transition-colors"
                        :class="darkMode ? 'bg-blue-600 text-white hover:bg-blue-500' : 'bg-blue-500 text-white hover:bg-blue-600'">
                        Apply
                    </button>
                    <button id="clear-date-filter" class="px-4 py-2 rounded-lg transition-colors"
                        :class="darkMode ? 'bg-gray-600 text-white hover:bg-gray-500' : 'bg-gray-300 text-gray-700 hover:bg-gray-400'">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Scrollable Table Container -->
        <div class="rounded-xl shadow-lg overflow-hidden flex justify-center transition-colors"
             :class="darkMode ? 'bg-gray-800' : 'bg-white'">
            <table class="min-w-full divide-y text-center transition-colors"
                   :class="darkMode ? 'divide-gray-700' : 'divide-gray-200'">
                <thead :class="darkMode ? 'bg-gray-700 text-white' : 'bg-gray-800 text-white'">
                    <tr>
                        <th class="py-2 px-3 text-sm font-semibold">No.</th>
                        <th class="py-2 px-3 text-sm font-semibold">Unique Code</th>
                        <th class="py-2 px-3 text-sm font-semibold">Part Number</th>
                        <th class="py-2 px-3 text-sm font-semibold">Process Type</th>
                        <th class="py-2 px-3 text-sm font-semibold">Progress</th>
                        <th class="py-2 px-3 text-sm font-semibold">Description</th>
                        <th class="py-2 px-3 text-sm font-semibold">Created</th>
                        <th class="py-2 px-3 text-sm font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body">
                    @foreach($requests as $index => $request)
                        <tr class="hover:bg-gray-300 transition-colors"
                            :class="darkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-300'">
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">
                                {{ $requests->firstItem() + $index }}
                            </td>
                            <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                                <a href="{{ route('manager.request.details', ['unique_code' => $request->unique_code, 'page' => request()->query('page', 1)]) }}">
                                    {{ $request->unique_code }}
                                </a>
                            </td>
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">{{ $request->part_number }}</td>
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">{{ $request->process_type }}</td>
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">{{ $request->current_process_index }}/{{ $request->total_processes }}</td>
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">{{ $request->description }}</td>
                            <td class="py-2 px-3 text-sm" :class="darkMode ? 'text-white' : 'text-gray-700'">{{ $request->created_at->format('M j, Y, g:i A') }}</td>
                            <td class="py-2 px-3 text-sm text-center">
                                @if($request->status === 'approved')
                                    <span class="text-green-500 text-xl">✔️</span>
                                @elseif($request->status === 'rejected')
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

            // Check if dark mode is enabled from localStorage
let isDarkMode = localStorage.getItem('darkMode') === 'true';

// Add the new request to the table
let newRow = `
    <tr id="request-row-${request.unique_code}" 
        class="hover:transition-colors ${isDarkMode ? 'bg-gray-800 text-white hover:bg-gray-700' : 'bg-white text-gray-700 hover:bg-gray-100'}">
        
        <td class="py-2 px-3 text-sm"></td> <!-- Empty, will be updated -->
        
        <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
            <a href="/manager/request/details/${request.unique_code}">
                ${request.unique_code}
            </a>
        </td>
        
        <td class="py-2 px-3 text-sm ${isDarkMode ? 'text-white' : 'text-gray-700'}">
            ${request.part_number || "N/A"}
        </td>
        
        <td class="py-2 px-3 text-sm ${isDarkMode ? 'text-white' : 'text-gray-700'}">
            ${request.process_type || "N/A"}
        </td>
        
        <td class="py-2 px-3 text-sm ${isDarkMode ? 'text-white' : 'text-gray-700'}">
            ${request.current_process_index}/${request.total_processes}
        </td>
        
        <td class="py-2 px-3 text-sm ${isDarkMode ? 'text-white' : 'text-gray-700'}">
            ${request.description || "N/A"}
        </td>
        
        <td class="py-2 px-3 text-sm ${isDarkMode ? 'text-white' : 'text-gray-700'}">
            ${createdAt}
        </td>
        
        <td class="py-2 px-3 text-sm text-center">
            ${getStatusIcon(request.manager_{{ Auth::guard('manager')->user()->manager_number }}_status)}
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