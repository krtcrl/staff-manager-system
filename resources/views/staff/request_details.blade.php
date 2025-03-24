@extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">
        <!-- Request Details Section at the Top -->
        <div class="bg-white dark:bg-gray-800 p-2 rounded-lg shadow-sm mb-2">
            <!-- Title and Created At Timestamp -->
            <div class="flex justify-between items-start mb-1">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                    Request Details
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Created: <span id="created-time">{{ $request->created_at->format('M j, Y, g:i A') }}</span>
                </p>
            </div>

            <!-- Part Number as a Header -->
            <div class="mb-2">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-300">{{ $request->part_number }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Part Number</p>
            </div>

            <!-- Two Columns for Request Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <!-- Left Column -->
                <div class="space-y-0.5">
             

                    <!-- Part Name -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Part Name:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $request->part_name }}</span>
                    </div>
                    <!-- Description -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Description:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $request->description }}</span>
                    </div>

                

                    <!-- Status -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Status:</span>
                        @if(str_contains($request->status, 'Approved by'))
                            <span class="text-green-500 font-semibold">{{ $request->status }}</span>
                        @elseif(str_contains($request->status, 'Rejected by'))
                            <span class="text-red-500 font-semibold">{{ $request->status }}</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400 font-semibold">Pending</span>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-0.5">
                    <!-- Process Type -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Process Type:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $request->process_type }}</span>
                    </div>
                    <!-- Progress -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Progress:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $request->current_process_index }}/{{ $request->total_processes }}</span>
                    </div>

                    <!-- Manager Status Section -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Manager Status:</span>
                        <div class="flex space-x-1 mt-0.5">
                            <!-- Approved -->
                            <div class="relative group">
                                <span id="approved-count" class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="approved-managers-list">
                                        @if(count($approvedManagers) > 0)
                                            @foreach($approvedManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one approved this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Rejected -->
                            <div class="relative group">
                                <span id="rejected-count" class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="rejected-managers-list">
                                        @if(count($rejectedManagers) > 0)
                                            @foreach($rejectedManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one rejected this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <!-- Pending -->
                            <div class="relative group">
                                <span id="pending-count" class="text-gray-500 dark:text-gray-400 font-semibold">{{ count($pendingManagers) }} Pending</span>
                                <div class="absolute bottom-full mb-1 hidden group-hover:block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 p-1 rounded-lg shadow-sm">
                                    <ul id="pending-managers-list">
                                        @if(count($pendingManagers) > 0)
                                            @foreach($pendingManagers as $manager)
                                                <li class="text-gray-800 dark:text-gray-300">{{ $manager }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-gray-800 dark:text-gray-300">No one pending actions on this request.</p>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to List and Edit Buttons -->
            <div class="mt-2 flex space-x-1">
                <a href="{{ route('staff.main', ['page' => request()->query('page', 1)]) }}" 
                   class="px-2 py-0.5 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Back to List
                </a>
                @if (!str_contains($request->status, 'Approved by') && !str_contains($request->status, 'Rejected by'))
                    <button id="editRequestButton" class="px-2 py-0.5 bg-green-500 text-white rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                        Edit Request
                    </button>
                @endif
            </div>
        </div>

  <!-- Attachment Section with Bottom Padding -->
<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-4">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Attachment</h2>
        
        @if ($request->attachment)
            <div class="flex space-x-2">
                <!-- Full Screen Button -->
                <button id="fullscreen-btn" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition dark:bg-gray-700 dark:hover:bg-gray-800">
                    Full Screen
                </button>
            </div>
        @endif
    </div>

    @if ($request->attachment)
        @php
            $extension = pathinfo($request->attachment, PATHINFO_EXTENSION);
            $fileUrl = asset('storage/' . $request->attachment);
        @endphp

        <div id="attachment-container" class="h-[600px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-12">
            
            @if (in_array($extension, ['pdf']))
                <!-- PDF Preview -->
                <iframe 
                    id="attachment-iframe"
                    src="{{ $fileUrl }}" 
                    class="w-full h-full border rounded-lg">
                </iframe>

            @elseif (in_array($extension, ['xls', 'xlsx', 'xlsb']))
              <!-- Excel Preview with Sheet Selection -->
@if (!empty($excelSheets))
    <!-- Sheet Selection Dropdown -->
    <div class="mb-4">
        <label for="sheet-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Sheet:</label>
        <select id="sheet-select" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            @foreach ($excelSheets as $sheetName => $sheetData)
                <option value="{{ $sheetName }}">{{ $sheetName }}</option>
            @endforeach
        </select>
    </div>

    <!-- Display Excel Data -->
    @foreach ($excelSheets as $sheetName => $sheetData)
        <div id="sheet-{{ $sheetName }}" class="sheet-content {{ $loop->first ? '' : 'hidden' }}">
            <table class="min-w-full bg-white dark:bg-gray-700">
                <thead>
                    <tr>
                        @if (!empty($sheetData[1]))
                            @foreach ($sheetData[1] as $header)
                                <th class="px-4 py-2 border border-gray-200 dark:border-gray-600">{{ $header ?? '' }}</th>
                            @endforeach
                        @else
                            <th class="px-4 py-2 border border-gray-200 dark:border-gray-600">No headers found</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($sheetData) > 1)
                        @foreach (array_slice($sheetData, 1) as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td class="px-4 py-2 border border-gray-200 dark:border-gray-600">{{ $cell ?? '' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($sheetData[1] ?? 1) }}" class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-center">No data found in this sheet.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach
@else
    <p class="text-gray-500">No sheets found in the Excel file.</p>
@endif

            @else
                <!-- Display unsupported file message -->
                <p class="text-gray-500">Unsupported file type: {{ $extension }}</p>
            @endif
        </div>

    @else
        <p class="text-gray-500">No attachment available.</p>
    @endif
</div>

<!-- JavaScript to Handle Sheet Selection and Download -->
<script>
// Handle sheet selection
document.getElementById('sheet-select').addEventListener('change', function() {
    const selectedSheet = this.value;
    document.querySelectorAll('.sheet-content').forEach(sheet => {
        sheet.classList.add('hidden');
    });
    document.getElementById(`sheet-${selectedSheet}`).classList.remove('hidden');
});

    
</script>




    </div>

    <!-- Edit Request Modal -->
    <div id="editRequestModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold mb-4">Edit Request</h2>
            <form id="editRequestForm" action="{{ route('staff.requests.update', $request->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Hidden input for request ID -->
                <input type="hidden" name="id" value="{{ $request->id }}">

                <!-- Editable fields -->
                <div class="space-y-4">
                    <div>
                        <label for="edit-part_number" class="block font-semibold">Part Number</label>
                        <input 
                            type="text" 
                            name="part_number" 
                            id="edit-part_number" 
                            value="{{ $request->part_number }}" 
                            class="w-full p-2 border rounded-lg bg-gray-100 cursor-not-allowed" 
                            readonly
                        >
                    </div>

                    <div>
                        <label for="edit-description" class="block font-semibold">Description</label>
                        <input 
                            type="text" 
                            name="description" 
                            id="edit-description" 
                            value="{{ $request->description }}" 
                            class="w-full p-2 border rounded-lg"
                        >
                    </div>

                    <!-- Attachment field -->
                    <div>
                        <label for="edit-attachment" class="block font-semibold">Attachment</label>
                        <input type="file" name="attachment" id="edit-attachment" class="w-full p-2 border rounded-lg">
                        @if ($request->attachment)
                            <p class="text-sm text-gray-500 mt-1">
                                Current Attachment: 
                                <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank" class="text-blue-500 hover:underline">View Attachment</a>
                                <button type="button" id="remove-attachment" class="text-red-500 hover:underline ml-2">Remove Attachment</button>
                            </p>
                        @else
                            <p class="text-sm text-gray-500 mt-1">No attachment uploaded.</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" id="cancelEditModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Update Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pusher Script for Real-Time Updates -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Open the modal when the "Edit Request" button is clicked
    document.getElementById('editRequestButton').addEventListener('click', function () {
        document.getElementById('editRequestModal').classList.remove('hidden');
    });

    // Close the modal when the "Cancel" button is clicked
    document.getElementById('cancelEditModal').addEventListener('click', function () {
        document.getElementById('editRequestModal').classList.add('hidden');
    });

    // Close the modal when clicking outside the modal
    document.getElementById('editRequestModal').addEventListener('click', function (event) {
        if (event.target === this) {
            document.getElementById('editRequestModal').classList.add('hidden');
        }
    });

    // Handle "Remove Attachment" button
    document.getElementById('remove-attachment')?.addEventListener('click', function () {
        // Add a hidden input to indicate that the attachment should be removed
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_attachment';
        input.value = '1';
        document.getElementById('editRequestForm').appendChild(input);

        // Hide the attachment link and remove button
        this.previousElementSibling.style.display = 'none';
        this.style.display = 'none';

        // Show a message indicating the attachment will be removed
        const message = document.createElement('p');
        message.className = 'text-sm text-gray-500 mt-1';
        message.textContent = 'Attachment will be removed.';
        this.parentNode.appendChild(message);
    });

    // Handle form submission
    document.getElementById('editRequestForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        formData.append('_method', 'PUT'); // Laravel treats this as PUT

        fetch(this.action, {
            method: 'POST', 
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Request updated successfully!');
                window.location.reload(); // Reload to reflect changes
            } else {
                alert('Failed to update the request. See console for details.');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('An error occurred. Please check the console for details.');
        });
    });

    // Fullscreen functionality for the attachment
    document.getElementById('fullscreen-btn')?.addEventListener('click', function () {
        const iframe = document.getElementById('attachment-iframe');
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { // Firefox
            iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, and Opera
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { // IE/Edge
            iframe.msRequestFullscreen();
        }
    });
</script>
@endsection