@extends('layouts.manager')

@section('content')
    <h1 class="text-2xl font-semibold">Welcome, {{ Auth::guard('manager')->user()->name }}!</h1>
    <p class="mt-4">You are logged in as a manager.</p>

    <!-- Request List Table -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Request List</h2>
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
            <tbody>
                @foreach($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b text-center">
                            <a href="{{ route('manager.request.details', $request->unique_code) }}" class="text-blue-500 hover:underline">
                                {{ $request->unique_code }}
                            </a>
                        </td>
                        <td class="py-2 px-4 border-b text-center">{{ $request->description }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_1_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_1_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_2_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_2_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_3_status === 'approved')
                                <span class="text-green-500">✔️</span>
                            @elseif($request->manager_3_status === 'rejected')
                                <span class="text-red-500">❌</span>
                            @else
                                <span class="text-gray-500">⏳</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
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

        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        var channel = pusher.subscribe('requests-channel');
        channel.bind('new-request', function(data) {
            let request = data.request;

            let newRow = `
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b text-center">
                        <a href="/manager/request/details/${request.unique_code}" class="text-blue-500 hover:underline">
                            ${request.unique_code}
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b text-center">${request.description || 'N/A'}</td>
                    <td class="py-2 px-4 border-b text-center">${getStatusIcon(request.manager_1_status)}</td>
                    <td class="py-2 px-4 border-b text-center">${getStatusIcon(request.manager_2_status)}</td>
                    <td class="py-2 px-4 border-b text-center">${getStatusIcon(request.manager_3_status)}</td>
                    <td class="py-2 px-4 border-b text-center">${getStatusIcon(request.manager_4_status)}</td>
                </tr>
            `;

            document.querySelector("tbody").innerHTML += newRow;
        });

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
