@extends('layouts.staff')

@section('content')
    <h1 class="text-2xl font-semibold">Welcome, {{ Auth::guard('staff')->user()->name }}!</h1>
    <p class="mt-4">You are logged in as a staff member.</p>

    <!-- Display Requests -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Request List</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Unique Code</th>
                    <th class="py-2 px-4 border-b">Description</th>
                    <th class="py-2 px-4 border-b">Manager 1</th>
                    <th class="py-2 px-4 border-b">Manager 2</th>
                    <th class="py-2 px-4 border-b">Manager 3</th>
                    <th class="py-2 px-4 border-b">Manager 4</th>
                </tr>
            </thead>
            <tbody id="requests-table-body">
            @foreach($requests as $request)
    <tr id="request-row-{{ $request->unique_code }}">
        <td class="py-2 px-4 border-b text-center">
            <a href="{{ route('staff.request.details', $request->unique_code) }}" class="text-blue-500 hover:underline">
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