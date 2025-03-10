@extends('layouts.staff')

@section('content')
    <div class="h-screen flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Column: Page Title and Request Details -->
                <div class="w-full lg:w-1/2 flex flex-col">
                    <h1 class="text-2xl font-semibold mb-4">Request Details</h1>

                    <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow">
                        <div class="flex-grow">
                            <p class="mb-2">Here are the details for the request with Unique Code: 
                                <span class="font-semibold">{{ $request->unique_code }}</span>.
                            </p>

                            <div class="space-y-2">
                                <div><span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}</div>
                                <div><span class="font-semibold">Description:</span> {{ $request->description }}</div>
                                <div><span class="font-semibold">Revision:</span> {{ $request->revision_type }}</div>

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
                                <div><span class="font-semibold">Part Number:</span> {{ $request->part_number }}</div>
                                <div><span class="font-semibold">Part Name:</span> {{ $request->part_name }}</div>
                                <div><span class="font-semibold">Process Type:</span> {{ $request->process_type }}</div>
                                <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}</div>

                                <!-- Manager Status Section -->
                                <div>
                                    <span class="font-semibold">Manager Status:</span>
                                    <div class="flex space-x-4 mt-2">
                                        <!-- Approved -->
                                        <div class="relative group">
                                            <span id="approved-count" class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                                <ul id="approved-managers-list">
                                                    @if(count($approvedManagers) > 0)
                                                        @foreach($approvedManagers as $manager)
                                                            <li>{{ $manager }}</li>
                                                        @endforeach
                                                    @else
                                                        <p>No one approved this request.</p>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- Rejected -->
                                        <div class="relative group">
                                            <span id="rejected-count" class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                                <ul id="rejected-managers-list">
                                                    @if(count($rejectedManagers) > 0)
                                                        @foreach($rejectedManagers as $manager)
                                                            <li>{{ $manager }}</li>
                                                        @endforeach
                                                    @else
                                                        <p>No one rejected this request.</p>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- Pending -->
                                        <div class="relative group">
                                            <span id="pending-count" class="text-gray-500 font-semibold">{{ count($pendingManagers) }} Pending</span>
                                            <div class="absolute bottom-full mb-2 hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                                                <ul id="pending-managers-list">
                                                    @if(count($pendingManagers) > 0)
                                                        @foreach($pendingManagers as $manager)
                                                            <li>{{ $manager }}</li>
                                                        @endforeach
                                                    @else
                                                        <p>No one pending actions on this request.</p>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back to List Button at the Bottom -->
                        <div class="mt-4">
                            <a href="{{ route('staff.main', ['page' => request()->query('page', 1)]) }}" 
                               class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

               <!-- Right Column: Attachment -->
<div class="w-full lg:w-1/2">
    <div class="bg-white p-4 rounded-lg shadow-sm h-[calc(100vh-10rem)]">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold text-gray-700">Attachment</h2>
            @if ($request->attachment)
                <button id="fullscreen-btn" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                    Full Screen
                </button>
            @endif
        </div>

        @if ($request->attachment)
            <div id="attachment-container" class="h-[calc(100%-3rem)] overflow-y-auto border border-gray-200 rounded-lg p-2 relative">
                <iframe 
                    id="attachment-iframe"
                    src="{{ asset('storage/' . $request->attachment) }}" 
                    class="w-full h-full border rounded-lg">
                </iframe>
            </div>
        @else
            <p class="text-gray-500">No attachment available.</p>
        @endif
    </div>
</div>

            </div>
        </div>
    </div>

    <!-- Pusher Script for Real-Time Updates -->
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
        document.getElementById("fullscreen-btn")?.addEventListener("click", function () {
    let iframeContainer = document.getElementById("attachment-container");

    if (!document.fullscreenElement) {
        if (iframeContainer.requestFullscreen) {
            iframeContainer.requestFullscreen();
        } else if (iframeContainer.mozRequestFullScreen) { // Firefox
            iframeContainer.mozRequestFullScreen();
        } else if (iframeContainer.webkitRequestFullscreen) { // Chrome, Safari
            iframeContainer.webkitRequestFullscreen();
        } else if (iframeContainer.msRequestFullscreen) { // IE/Edge
            iframeContainer.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
});
    </script>
@endsection