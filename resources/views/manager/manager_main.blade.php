@extends('layouts.manager')

@section('content')
    <h1 class="text-2xl font-semibold">Welcome, {{ Auth::guard('manager')->user()->name }}!</h1>
    <p class="mt-4">You are logged in as a manager.</p>

    <!-- Main Container -->
    <div class="mt-8 flex flex-col lg:flex-row gap-6 h-[calc(100vh-200px)]">
        <!-- Left Column: Statistics -->
        <div class="w-full lg:w-1/2">
            <!-- Statistics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6">
                <!-- New Requests Today -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">New Requests Today</h3>
                    <p id="new-requests-today" class="text-2xl font-bold text-purple-500">{{ $newRequestsToday }}</p>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold">Pending Requests for Manager {{ Auth::guard('manager')->user()->manager_number }}</h3>
                    <p id="pending-requests" class="text-2xl font-bold text-yellow-500">{{ $pendingRequests }}</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Request List -->
        <div class="w-full lg:w-1/2">
            <h2 class="text-xl font-semibold mb-4">Request List</h2>
            <div class="overflow-y-auto h-[calc(100vh-300px)]">
                <table class="min-w-full bg-white border border-gray-300 shadow-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b font-semibold">Unique Code</th>
                            <th class="py-2 px-4 border-b font-semibold">Description</th>
                            <th class="py-2 px-4 border-b font-semibold">Manager 1</th>
                            <th class="py-2 px-4 border-b font-semibold">Manager 2</th>
                            <th class="py-2 px-4 border-b font-semibold">Manager 3</th>
                            <th class="py-2 px-4 border-b font-semibold">Manager 4</th>
                        </tr>
                    </thead>
                    <tbody id="requests-table-body">
                        @foreach($requests as $request)
                            <tr id="request-row-{{ $request->unique_code }}" class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('manager.request.details', $request->unique_code) }}" class="text-blue-500 hover:underline">
                                        {{ $request->unique_code }}
                                    </a>
                                </td>
                                <td class="py-2 px-4 border-b text-center">{{ $request->description }}</td>
                                <td class="py-2 px-4 border-b text-center manager-1-status">
                                    @if($request->manager_1_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_1_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center manager-2-status">
                                    @if($request->manager_2_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_2_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center manager-3-status">
                                    @if($request->manager_3_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_3_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b text-center manager-4-status">
                                    @if($request->manager_4_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_4_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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

            // Check if the request was created today
            let requestDate = new Date(request.created_at);
            let today = new Date();
            if (requestDate.toDateString() === today.toDateString()) {
                // Increment the new requests count
                let newRequestsTodayElement = document.getElementById('new-requests-today');
                let currentCount = parseInt(newRequestsTodayElement.textContent);
                newRequestsTodayElement.textContent = currentCount + 1;
            }

            // Add the new request to the table
            let newRow = `
                <tr id="request-row-${request.unique_code}" class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">
                        <a href="/manager/request/details/${request.unique_code}" class="text-blue-500 hover:underline">
                            ${request.unique_code}
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b text-center">${request.description || 'N/A'}</td>
                    <td class="py-2 px-4 border-b text-center manager-1-status">${getStatusIcon(request.manager_1_status)}</td>
                    <td class="py-2 px-4 border-b text-center manager-2-status">${getStatusIcon(request.manager_2_status)}</td>
                    <td class="py-2 px-4 border-b text-center manager-3-status">${getStatusIcon(request.manager_3_status)}</td>
                    <td class="py-2 px-4 border-b text-center manager-4-status">${getStatusIcon(request.manager_4_status)}</td>
                </tr>
            `;

            document.querySelector("#requests-table-body").innerHTML += newRow;
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
    </script>
@endsection