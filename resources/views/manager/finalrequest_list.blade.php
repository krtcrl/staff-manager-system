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

<div class="container mx-auto p-4">
    <!-- Header for Final Request List -->
    <div class="mb-4 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-300">Final Request List</h2>

        <!-- Search and Date Filter Container -->
        <div class="flex items-center space-x-4">
            <!-- Search Bar -->
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-center">
            <thead>
                <tr>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">No.</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Unique Code</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Part Number</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Part Name</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Description</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Created</th>
                    <th class="py-2 px-3 text-sm font-semibold bg-gray-800 text-white">Status</th>
                </tr>
            </thead>
            <tbody id="finalrequests-table-body">
                @foreach($finalRequests as $index => $request)
                    @php
                        $manager = Auth::guard('manager')->user();
                        $managerNumber = $manager ? $manager->manager_number : null;
                        $statusColumn = $managerNumber && isset($managerColumnMap[$managerNumber])
                            ? $managerColumnMap[$managerNumber]
                            : 'N/A';

                        $status = $statusColumn !== 'N/A' ? $request->{$statusColumn} : 'N/A';
                    @endphp
                    <tr id="finalrequest-row-{{ $request->unique_code }}" class="hover:bg-gray-100 transition-colors">
                        <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequests->firstItem() + $index }}</td>
                        <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                            <a href="{{ route('manager.finalrequest.details', ['unique_code' => $request->unique_code, 'page' => request()->query('page', 1)]) }}">
                                {{ $request->unique_code }}
                            </a>
                        </td>
                        <td class="py-2 px-3 text-sm text-gray-700">{{ $request->part_number }}</td>
                        <td class="py-2 px-3 text-sm text-gray-700">{{ $request->part_name }}</td>
                        <td class="py-2 px-3 text-sm text-gray-700">{{ $request->description }}</td>
                        <td class="py-2 px-3 text-sm text-gray-700">{{ $request->created_at->format('M j, Y, g:i A') }}</td>
                        <td class="py-2 px-3 text-sm text-center">
                            {!! getStatusIcon($status) !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
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

        let createdAt = new Date(request.created_at).toLocaleString("en-US", {
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "numeric",
            minute: "numeric",
            hour12: true
        });

        let newRow = `
            <tr id="finalrequest-row-${request.unique_code}" class="hover:bg-gray-50 transition-colors">
                <td class="py-2 px-3 text-sm text-gray-700"></td>
                <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                    <a href="/manager/finalrequest/details/${request.unique_code}">
                        ${request.unique_code}
                    </a>
                </td>
                <td class="py-2 px-3 text-sm text-gray-700">${request.part_number || "N/A"}</td>
                <td class="py-2 px-3 text-sm text-gray-700">${request.part_name || "N/A"}</td>
                <td class="py-2 px-3 text-sm text-gray-700">${request.description || "N/A"}</td>
                <td class="py-2 px-3 text-sm text-gray-700">${createdAt}</td>
                <td class="py-2 px-3 text-sm text-center">${getStatusIcon(request.status)}</td>
            </tr>
        `;

        document.querySelector("#finalrequests-table-body").innerHTML += newRow;
    });
</script>

@endsection
