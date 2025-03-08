@extends('layouts.manager')

@section('content')
    <!-- Main Container -->
    <div class="container mx-auto p-6">
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ Auth::guard('manager')->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">You are logged in as a manager.</p>
        </div>

        <!-- Grid Layout for Statistics and Request List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Statistics -->
            <div class="space-y-6">
                <!-- New Requests Today -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700">New Requests Today</h3>
                    <p id="new-requests-today" class="text-4xl font-bold text-purple-600 mt-2">{{ $newRequestsToday }}</p>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700">Pending Requests for Manager {{ Auth::guard('manager')->user()->manager_number }}</h3>
                    <p id="pending-requests" class="text-4xl font-bold text-yellow-500 mt-2">{{ $pendingRequests }}</p>
                </div>
            </div>

            <!-- Right Column: Request List -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header for Request List -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Request List</h2>
                </div>

                <!-- Scrollable Table Container -->
                <div class="overflow-y-auto h-[calc(100vh-350px)]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Unique Code</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Description</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Manager 1</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Manager 2</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Manager 3</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700">Manager 4</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr id="request-row-{{ $request->unique_code }}" class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-sm text-blue-500 hover:underline">
                                        <a href="{{ route('manager.request.details', $request->unique_code) }}">
                                            {{ $request->unique_code }}
                                        </a>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $request->description }}</td>
                                    <td class="py-3 px-4 text-sm text-center">
                                        @if($request->manager_1_status === 'approved')
                                            <span class="text-green-500">✔️</span>
                                        @elseif($request->manager_1_status === 'rejected')
                                            <span class="text-red-500">❌</span>
                                        @else
                                            <span class="text-gray-500">⏳</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-center">
                                        @if($request->manager_2_status === 'approved')
                                            <span class="text-green-500">✔️</span>
                                        @elseif($request->manager_2_status === 'rejected')
                                            <span class="text-red-500">❌</span>
                                        @else
                                            <span class="text-gray-500">⏳</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-center">
                                        @if($request->manager_3_status === 'approved')
                                            <span class="text-green-500">✔️</span>
                                        @elseif($request->manager_3_status === 'rejected')
                                            <span class="text-red-500">❌</span>
                                        @else
                                            <span class="text-gray-500">⏳</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sm text-center">
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
                <tr id="request-row-${request.unique_code}" class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 text-sm text-blue-500 hover:underline">
                        <a href="/manager/request/details/${request.unique_code}">
                            ${request.unique_code}
                        </a>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-700">${request.description || 'N/A'}</td>
                    <td class="py-3 px-4 text-sm text-center">${getStatusIcon(request.manager_1_status)}</td>
                    <td class="py-3 px-4 text-sm text-center">${getStatusIcon(request.manager_2_status)}</td>
                    <td class="py-3 px-4 text-sm text-center">${getStatusIcon(request.manager_3_status)}</td>
                    <td class="py-3 px-4 text-sm text-center">${getStatusIcon(request.manager_4_status)}</td>
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