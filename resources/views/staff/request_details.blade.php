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

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm mt-4 border border-gray-100 dark:border-gray-700">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-5 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
        Attachment
    </h2>

    @if ($request->attachment || $request->final_approval_attachment)
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 shadow-xs">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Filename</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Size</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Pre-Approval Attachment -->
                    @if ($request->attachment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Pre-Approval
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $request->attachment }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $ext = pathinfo($request->attachment, PATHINFO_EXTENSION);
                                @endphp
                                <div class="flex items-center gap-1.5">
                                    @if($ext === 'xlsx')
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Excel (.xlsx)
                                    @elseif($ext === 'xls')
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Excel (.xls)
                                    @elseif($ext === 'xlsb')
                                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                        Excel Binary (.xlsb)
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                        Unknown
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $path = storage_path("app/public/attachments/{$request->attachment}");
                                    $size = file_exists($path) ? round(filesize($path) / 1024, 2) . ' KB' : 'N/A';
                                @endphp
                                {{ $size }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('download.attachment', ['filename' => rawurlencode($request->attachment)]) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Download
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endif

                    <!-- Final Approval Attachment -->
                    @if ($request->final_approval_attachment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Final Approval
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $request->final_approval_attachment }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $extFinal = pathinfo($request->final_approval_attachment, PATHINFO_EXTENSION);
                                @endphp
                                <div class="flex items-center gap-1.5">
                                    @if($extFinal === 'xlsx')
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Excel (.xlsx)
                                    @elseif($extFinal === 'xls')
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Excel (.xls)
                                    @elseif($extFinal === 'xlsb')
                                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                        Excel Binary (.xlsb)
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                        Unknown
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $finalPath = storage_path("app/public/final_approval_attachments/{$request->final_approval_attachment}");
                                    $finalSize = file_exists($finalPath) ? round(filesize($finalPath) / 1024, 2) . ' KB' : 'N/A';
                                @endphp
                                {{ $finalSize }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('download.final_attachment', ['filename' => $request->final_approval_attachment]) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    Download
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-200">No attachments</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">There are no files attached to this request.</p>
        </div>
    @endif
</div>


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
                                <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank" class="text-blue-500 hover:underline">Download</a>
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
</script>
@endsection