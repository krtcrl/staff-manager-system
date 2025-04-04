@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-2"> <!-- Reduced padding -->
    
    <!-- Header for Final Request List -->
    <div class="mb-1 flex justify-between items-center"> <!-- Reduced margin -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300">Final Approval List</h2> <!-- Dark mode support -->

        <!-- Search and Date Filter Container -->
        <div class="flex items-center space-x-2"> <!-- Reduced spacing -->
            
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                    class="pl-8 pr-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:placeholder-gray-400">
                <svg class="absolute left-2 top-2 h-4 w-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Date Filter -->
            <div class="flex items-center space-x-1"> <!-- Reduced spacing -->
                <input type="date" id="start-date" 
                    class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                <span class="text-gray-500 dark:text-gray-300">to</span>
                <input type="date" id="end-date" 
                    class="px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                <button id="apply-date-filter" 
                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">Filter</button>
                <button id="clear-date-filter" 
                    class="px-3 py-1 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400 transition dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">Clear</button>
            </div>
        </div>
    </div>

    <!-- Compact Scrollable Table with Hover Effects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-x-auto max-w-full max-h-[600px] mt-2"> 
        <table class="w-full border-collapse border border-gray-300 dark:border-gray-700 text-center text-sm">
            <thead class="bg-gray-800 dark:bg-gray-900"> <!-- Dark background for header -->
                <tr>
                    <th class="py-1 px-2 border bg-gray-800 dark:bg-gray-900 text-white">No.</th>
                    <th class="py-1 px-2 border bg-gray-800 dark:bg-gray-900 text-white">Unique Code</th>
                    <th class="py-1 px-2 border bg-gray-800 dark:bg-gray-900 text-white">Part Number</th>
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">Capacity Planning</th> <!-- Manager 1 Status -->
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">Planning</th> <!-- Manager 2 Status -->
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">Product</th> <!-- Manager 3 Status -->
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">EE</th> <!-- Manager 4 Status -->
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">QAE</th> <!-- Manager 5 Status -->
                    <th class="py-1 px-2 border bg-blue-900 dark:bg-blue-800 text-white">General Manager</th> <!-- Manager 6 Status -->
                    <th class="py-1 px-2 border bg-gray-800 dark:bg-gray-900 text-white">Created</th>
                </tr>
            </thead>
            <tbody id="final-requests-table-body" class="bg-white dark:bg-gray-800">
                @if($finalRequests->isEmpty())
                    <tr>
                        <td colspan="10" class="py-4 text-center text-gray-500 dark:text-gray-400">
                            No requests for final approval at the moment.
                        </td>
                    </tr>
                @else
                    @foreach($finalRequests as $index => $finalRequest)
                        <tr class="border border-gray-300 dark:border-gray-700 transition hover:bg-gray-100 dark:hover:bg-gray-700 hover:shadow-md">
                            <td class="py-1 px-2 border text-gray-800 dark:text-gray-300">{{ $finalRequests->firstItem() + $index }}</td>
                            <td class="py-1 px-2 text-blue-500 hover:underline border">
                                <a href="{{ route('staff.final.details', ['unique_code' => $finalRequest->unique_code]) }}" class="dark:text-blue-400">
                                    {{ $finalRequest->unique_code }}
                                </a>
                            </td>
                            <td class="py-1 px-2 border text-gray-800 dark:text-gray-300">{{ $finalRequest->part_number }}</td>

                            <!-- Manager 1 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_1_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_1_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 2 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_2_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_2_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 3 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_3_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_3_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 4 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_4_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_4_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 5 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_5_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_5_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 6 Status -->
                            <td class="py-1 px-2 text-center border">
                                @if($finalRequest->manager_6_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_6_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">⏳</span>
                                @endif
                            </td>
                            <td class="py-1 px-2 border text-gray-800 dark:text-gray-300">
                                {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 text-sm">
        {{ $finalRequests->links() }}
    </div>
</div>



<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Initialize Pusher
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    // Subscribe to the finalrequests channel
    var channel = pusher.subscribe('finalrequests-channel');

    // Listen for new final request events
    channel.bind("new-finalrequest", function (data) {
        let finalRequest = data.finalRequest;

        // Format the created_at date
        let createdAt = new Date(finalRequest.created_at).toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true
        });

        // Create the new row for the final request
        let newRow = `
            <tr class="border border-gray-300 dark:border-gray-700 transition hover:bg-gray-100 dark:hover:bg-gray-700 hover:shadow-md">
                <td class="py-1 px-2 border text-gray-800 dark:text-gray-300"></td> <!-- Empty, will be updated -->
                <td class="py-1 px-2 text-blue-500 hover:underline border">
                    <a href="/staff/final/details/${finalRequest.unique_code}" class="dark:text-blue-400">
                        ${finalRequest.unique_code}
                    </a>
                </td>
                <td class="py-1 px-2 border text-gray-800 dark:text-gray-300">${finalRequest.part_number || "N/A"}</td>
                <!-- Manager 1 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_1_status)}
                </td>
                <!-- Manager 2 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_2_status)}
                </td>
                <!-- Manager 3 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_3_status)}
                </td>
                <!-- Manager 4 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_4_status)}
                </td>
                <!-- Manager 5 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_5_status)}
                </td>
                <!-- Manager 6 Status -->
                <td class="py-1 px-2 text-center border">
                    ${getStatusIcon(finalRequest.manager_6_status)}
                </td>
                <td class="py-1 px-2 border text-gray-800 dark:text-gray-300">${createdAt}</td>
            </tr>
        `;

        // Prepend the new row to the top of the table body
        let tableBody = document.querySelector("#final-requests-table-body");
        tableBody.insertAdjacentHTML("afterbegin", newRow);

        updateRowNumbers(); // Update numbering after adding a new row
    });

    // Function to get the status icon based on the status
    function getStatusIcon(status) {
        if (status === 'approved') {
            return '<span class="text-green-500">✔️</span>';
        } else if (status === 'rejected') {
            return '<span class="text-red-500">❌</span>';
        } else {
            return '<span class="text-gray-500 dark:text-gray-400">⏳</span>';
        }
    }

    // Function to update row numbers dynamically
    function updateRowNumbers() {
        let rows = document.querySelectorAll("#final-requests-table-body tr");
        rows.forEach((row, index) => {
            row.querySelector("td:first-child").textContent = index + 1; // Update "No." column
        });
    }

    // Search Functionality
    document.getElementById("search-bar").addEventListener("input", function () {
        let searchTerm = this.value.toLowerCase();
        let rows = document.querySelectorAll("#final-requests-table-body tr");

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

    // Apply Date Filter
    document.getElementById("apply-date-filter").addEventListener("click", function () {
        let startDateInput = document.getElementById("start-date").value;
        let endDateInput = document.getElementById("end-date").value;

        let startDate = startDateInput ? new Date(startDateInput) : null;
        let endDate = endDateInput ? new Date(endDateInput) : null;

        let rows = document.querySelectorAll("#final-requests-table-body tr");

        rows.forEach((row) => {
            let dateCell = row.querySelector("td:nth-child(11)").textContent.trim(); // 11th column = Created Date
            let requestDate = new Date(dateCell);

            if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        updateRowNumbers(); // Update numbering after filtering
    });

    // Clear Date Filter
    document.getElementById("clear-date-filter").addEventListener("click", function () {
        document.getElementById("start-date").value = "";
        document.getElementById("end-date").value = "";

        let rows = document.querySelectorAll("#final-requests-table-body tr");
        rows.forEach((row) => (row.style.display = ""));

        updateRowNumbers(); // Restore correct numbering
    });
</script>
@endsection