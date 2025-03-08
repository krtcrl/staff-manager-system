@extends('layouts.staff')

@section('content')
    <h1 class="text-2xl font-semibold">Request Details</h1>
    <p class="mt-4">Here are the details for the request with Unique Code: <span class="font-semibold">{{ $request->unique_code }}</span>.</p>

    <!-- Two-Column Layout for Request Details and Attachment -->
    <div class="mt-8 flex flex-col lg:flex-row gap-6">
        <!-- Left Column: Request Details -->
        <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow-sm">
            <div class="space-y-4">
                <div>
                    <span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}
                </div>
                <div>
                    <span class="font-semibold">Description:</span> {{ $request->description }}
                </div>
                <div>
                    <span class="font-semibold">Status:</span>
                    @if(str_contains($request->status, 'Approved by'))
                        <span class="text-green-500 font-semibold">{{ $request->status }}</span>
                    @elseif(str_contains($request->status, 'Rejected by'))
                        <span class="text-red-500 font-semibold">{{ $request->status }}</span>
                    @else
                        <span class="text-gray-500 font-semibold">Pending</span>
                    @endif
                </div>
                <div>
                    <span class="font-semibold">Part Number:</span> {{ $request->part_number }}
                </div>
                <div>
                    <span class="font-semibold">Part Name:</span> {{ $request->part_name }}
                </div>
                <div>
                    <span class="font-semibold">Process Type:</span> {{ $request->process_type }}
                </div>
                <div>
                    <span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}
                </div>
                <div>
                    <span class="font-semibold">Manager Status:</span>
                    <div class="flex space-x-4 mt-2">
                        <!-- Approved -->
                        <div class="relative group">
                            <span id="approved-count" class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                            <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                <ul id="approved-managers-list">
                                    @if(count($approvedManagers) > 0)
                                        @foreach($approvedManagers as $manager)
                                            <li>{{ $manager }}</li>
                                        @endforeach
                                    @else
                                        <p>No managers have approved this request.</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- Rejected -->
                        <div class="relative group">
                            <span id="rejected-count" class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                            <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                <ul id="rejected-managers-list">
                                    @if(count($rejectedManagers) > 0)
                                        @foreach($rejectedManagers as $manager)
                                            <li>{{ $manager }}</li>
                                        @endforeach
                                    @else
                                        <p>No managers have rejected this request.</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- Pending -->
                        <div class="relative group">
                            <span id="pending-count" class="text-gray-500 font-semibold">{{ count($pendingManagers) }} Pending</span>
                            <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                <ul id="pending-managers-list">
                                    @if(count($pendingManagers) > 0)
                                        @foreach($pendingManagers as $manager)
                                            <li>{{ $manager }}</li>
                                        @endforeach
                                    @else
                                        <p>No managers have pending actions on this request.</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Attachment -->
        <div class="w-full lg:w-1/2 bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Attachment</h2>
            @if ($request->attachment)
                <!-- Display PDF in an iframe with dynamic height -->
                <div class="h-[400px]">
                    <iframe 
                        src="{{ asset('storage/' . $request->attachment) }}" 
                        class="w-full h-full border rounded-lg"
                    >
                        Your browser does not support PDFs. 
                        <a href="{{ asset('storage/' . $request->attachment) }}" class="text-blue-500 hover:underline">
                            Download the PDF
                        </a>
                    </iframe>
                </div>
            @else
                <p class="text-gray-500">No attachment available.</p>
            @endif
        </div>
    </div>

    <!-- Sticky Back Button -->
    <div class="p-4 bg-gray-100 border-t fixed bottom-0 w-full">
        <a href="{{ route('staff.dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Back to Dashboard
        </a>
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

            // Update the manager status counts and lists
            updateManagerStatus(request);
        });

        // Function to update manager status counts and lists
        function updateManagerStatus(request) {
            let approvedManagers = [];
            let rejectedManagers = [];
            let pendingManagers = [];

            for (let i = 1; i <= 4; i++) {
                let status = request[`manager_${i}_status`];
                if (status === 'approved') {
                    approvedManagers.push(`Manager ${i}`);
                } else if (status === 'rejected') {
                    rejectedManagers.push(`Manager ${i}`);
                } else {
                    pendingManagers.push(`Manager ${i}`);
                }
            }

            // Update the counts
            document.getElementById('approved-count').textContent = `${approvedManagers.length} Approved`;
            document.getElementById('rejected-count').textContent = `${rejectedManagers.length} Rejected`;
            document.getElementById('pending-count').textContent = `${pendingManagers.length} Pending`;

            // Update the lists
            updateManagerList('approved-managers-list', approvedManagers);
            updateManagerList('rejected-managers-list', rejectedManagers);
            updateManagerList('pending-managers-list', pendingManagers);
        }

        // Function to update the manager list
        function updateManagerList(listId, managers) {
            let listElement = document.getElementById(listId);
            listElement.innerHTML = '';

            if (managers.length > 0) {
                managers.forEach(manager => {
                    let li = document.createElement('li');
                    li.textContent = manager;
                    listElement.appendChild(li);
                });
            } else {
                let p = document.createElement('p');
                p.textContent = `No managers have ${listId.replace('-managers-list', '')} this request.`;
                listElement.appendChild(p);
            }
        }
    </script>
@endsection