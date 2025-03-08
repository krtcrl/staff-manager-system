@extends('layouts.manager')

@section('content')
    <div class="container mx-auto p-6">
        <!-- Header for Request List -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Request List</h2>
        </div>

        <!-- Scrollable Table Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-y-auto h-[calc(100vh-200px)]">
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
                    <tbody class="divide-y divide-gray-200" id="requests-table-body">
                        @foreach($requests as $request)
                            <tr id="request-row-{{ $request->unique_code }}" class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 text-sm text-blue-500 hover:underline">
                                    <a href="{{ route('manager.request.details', $request->unique_code) }}">
                                        {{ $request->unique_code }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">{{ $request->description }}</td>
                                <td class="py-3 px-4 text-sm text-center manager-1-status">
                                    @if($request->manager_1_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_1_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-center manager-2-status">
                                    @if($request->manager_2_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_2_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-center manager-3-status">
                                    @if($request->manager_3_status === 'approved')
                                        <span class="text-green-500">✔️</span>
                                    @elseif($request->manager_3_status === 'rejected')
                                        <span class="text-red-500">❌</span>
                                    @else
                                        <span class="text-gray-500">⏳</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm text-center manager-4-status">
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
        channel.bind('new-request', function(data) {
            let request = data.request;

            // Add the new request to the table
            let newRow = `
                <tr id="request-row-${request.unique_code}" class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 text-sm text-blue-500 hover:underline">
                        <a href="/manager/request/details/${request.unique_code}">
                            ${request.unique_code}
                        </a>
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-700">${request.description || 'N/A'}</td>
                    <td class="py-3 px-4 text-sm text-center manager-1-status">${getStatusIcon(request.manager_1_status)}</td>
                    <td class="py-3 px-4 text-sm text-center manager-2-status">${getStatusIcon(request.manager_2_status)}</td>
                    <td class="py-3 px-4 text-sm text-center manager-3-status">${getStatusIcon(request.manager_3_status)}</td>
                    <td class="py-3 px-4 text-sm text-center manager-4-status">${getStatusIcon(request.manager_4_status)}</td>
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