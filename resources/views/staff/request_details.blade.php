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
                            <div class="space-y-2">
                                <div><span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}</div>
                                <div><span class="font-semibold">Part Number:</span> {{ $request->part_number }}</div>
                                <div><span class="font-semibold">Part Name:</span> {{ $request->part_name }}</div>
                                
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

                        <!-- Back to List and Edit Buttons at the Bottom -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('staff.main', ['page' => request()->query('page', 1)]) }}" 
                               class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Back to List
                            </a>
                            @if (!str_contains($request->status, 'Approved by') && !str_contains($request->status, 'Rejected by'))
                                <button id="editRequestButton" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    Edit Request
                                </button>
                            @endif
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
                    <label for="edit-part_name" class="block font-semibold">Part Name</label>
                    <input 
                        type="text" 
                        name="part_name" 
                        id="edit-part_name" 
                        value="{{ $request->part_name }}" 
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
    value="" 
    class="w-full p-2 border rounded-lg" > <!-- Extra closing tag -->

                </div>

                <div>
                    <label for="edit-revision_type" class="block font-semibold">Revision Type</label>
                    <input 
                        type="text" 
                        name="revision_type" 
                        id="edit-revision_type" 
                        value="{{ $request->revision_type }}" 
                        class="w-full p-2 border rounded-lg"
                    >
                </div>

                <div>
                    <label for="edit-uph" class="block font-semibold">UPH (Units Per Hour)</label>
                    <input 
                        type="number" 
                        name="uph" 
                        id="edit-uph" 
                        value="{{ $request->uph }}" 
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


    <!-- Pusher Script for Real-Time Updates -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Open the modal when the "Edit Request" button is clicked
        document.getElementById('editRequestButton').addEventListener('click', function () {
            document.getElementById('editRequestModal').classList.remove('hidden');

             // ✅ Always clear the description field when opening the modal
        document.getElementById('edit-description').value = '';
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