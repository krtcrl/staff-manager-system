@extends('layouts.manager')

@section('content')

@php
// Define the getStatusIcon() function at the top
function getStatusIcon($status) {
    if ($status === 'approved') {
        return '<span class="text-green-500 text-xl">✔️</span>';
    } elseif ($status === 'rejected') {
        return '<span class="text-red-500 text-xl">❌</span>';
    } else {
        return '<span class="text-gray-500 text-xl">⏳</span>';
    }
}

// Map manager numbers to their corresponding table column names
$managerColumnMap = [
    1 => 'manager_1_status',
    5 => 'manager_2_status',
    6 => 'manager_3_status',
    7 => 'manager_4_status',
    8 => 'manager_5_status',
    9 => 'manager_6_status'
];
@endphp
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
<div class="container mx-auto p-4 bg-gray-100 dark:bg-gray-900"> <!-- Dark mode applied -->
    <!-- Header for Final Request List -->
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-300">Final Approval List</h2>

        <!-- Search and Date Filter Container -->
        <div class="flex items-center space-x-4">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"> <!-- Dark mode -->
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" 
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 
                          7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden"> <!-- Dark mode -->
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center"> <!-- Dark mode -->
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-2 px-3 text-sm font-semibold">No.</th>
                    <th class="py-2 px-3 text-sm font-semibold">Unique Code</th>
                    <th class="py-2 px-3 text-sm font-semibold">Part Number</th>
                    <th class="py-2 px-3 text-sm font-semibold">Part Name</th>
                    <th class="py-2 px-3 text-sm font-semibold">Created</th>
                    <th class="py-2 px-3 text-sm font-semibold">Status</th>
                </tr>
            </thead>
            <tbody id="finalrequests-table-body" class="bg-white dark:bg-gray-900"> <!-- Dark mode -->
                @foreach($finalRequests as $index => $request)
                    @php
                        $manager = Auth::guard('manager')->user();
                        $managerNumber = $manager ? $manager->manager_number : null;
                        $statusColumn = $managerNumber && isset($managerColumnMap[$managerNumber])
                            ? $managerColumnMap[$managerNumber]
                            : 'N/A';

                        $status = $statusColumn !== 'N/A' ? $request->{$statusColumn} : 'N/A';

                        // Convert created_at to GMT+8
                        $createdAtGMT8 = $request->created_at
                            ->setTimezone('Asia/Manila') // GMT+8
                            ->format('M j, Y, g:i A');
                    @endphp
                    <tr id="finalrequest-row-{{ $request->unique_code }}" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"> <!-- Dark mode -->
                        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $finalRequests->firstItem() + $index }}</td>
                        <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                            <a href="{{ route('manager.finalrequest.details', ['unique_code' => $request->unique_code, 'page' => request()->query('page', 1)]) }}">
                                {{ $request->unique_code }}
                            </a>
                        </td>
                        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $request->part_number }}</td>
                        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $request->part_name }}</td>
                        <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $createdAtGMT8 }}</td> <!-- GMT+8 -->
                        <td class="py-2 px-3 text-sm text-center">
                            {!! getStatusIcon($status) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination with dark mode -->
    <div class="mt-4 dark:text-gray-300">
        {{ $finalRequests->appends(request()->except('page'))->links() }}
    </div>
</div>

<!-- Pusher Script -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    var channel = pusher.subscribe('finalrequests-channel');

    // Handle new request event
    channel.bind("new-finalrequest", function (data) {
        let request = data.finalRequest;

        // Convert the created_at time to GMT+8
        let createdAt = new Date(request.created_at).toLocaleString("en-US", {
            timeZone: "Asia/Manila",
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true
        });

        let newRow = `
            <tr id="finalrequest-row-${request.unique_code}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300"></td>
                <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                    <a href="/manager/finalrequest/details/${request.unique_code}">
                        ${request.unique_code}
                    </a>
                </td>
                <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${request.part_number || "N/A"}</td>
                <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${request.part_name || "N/A"}</td>
                <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">${createdAt}</td>
                <td class="py-2 px-3 text-sm text-center">${getStatusIcon(request.status)}</td>
            </tr>
        `;

        document.querySelector("#finalrequests-table-body").innerHTML += newRow;
    });
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

</script>

@endsection
