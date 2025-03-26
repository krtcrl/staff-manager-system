@extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
<div class="flex-1 overflow-y-auto p-4 pb-8">
    <!-- Request Details Card -->
<div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-lg border border-gray-300 dark:border-gray-600 transition duration-300 ease-in-out">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                Pre-Approval Details
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Part Number: <span class="font-medium">{{ $request->part_number }}</span>
            </p>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">
            Created: <span id="created-time">{{ $request->created_at->format('M j, Y, g:i A') }}</span>
        </p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Left Column -->
        <div class="space-y-3">
            <!-- Part Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Part Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Part Name</p>
                        <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $request->part_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                        <p class="text-gray-800 dark:text-gray-300">{{ $request->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Process Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Process Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Process Type</p>
                        <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $request->process_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Progress</p>
                        <div class="flex items-center gap-2">
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                     style="width: {{ ($request->current_process_index / $request->total_processes) * 100 }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ $request->current_process_index }}/{{ $request->total_processes }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-3">
            <!-- Status Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Status Overview</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Status</p>
                        @if(str_contains($request->status, 'Approved by'))
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                {{ $request->status }}
                            </div>
                        @elseif(str_contains($request->status, 'Rejected by'))
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                {{ $request->status }}
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                Pending
                            </div>
                        @endif
                    </div>

                    <!-- Manager Status -->
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Manager Approvals</p>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <!-- Approved -->
                            <div class="bg-green-50 dark:bg-green-900/30 p-2 rounded-lg">
                                <span class="text-green-600 dark:text-green-400 font-bold text-lg">{{ count($approvedManagers) }}</span>
                                <p class="text-xs text-green-600 dark:text-green-400">Approved</p>
                            </div>

                            <!-- Rejected -->
                            <div class="bg-red-50 dark:bg-red-900/30 p-2 rounded-lg">
                                <span class="text-red-600 dark:text-red-400 font-bold text-lg">{{ count($rejectedManagers) }}</span>
                                <p class="text-xs text-red-600 dark:text-red-400">Rejected</p>
                            </div>

                            <!-- Pending -->
                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400 font-bold text-lg">{{ count($pendingManagers) }}</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           <!-- ✅ Attachment Section with Icon -->
<div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
    <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" 
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V15" />
            <path d="M7 10L12 15L17 10" />
            <path d="M12 15V3" />
        </svg>
        Attachment
    </h3>
    
    @if($request->attachment)
        <div class="text-sm">
            <p class="text-gray-500 dark:text-gray-400">PROCESS STUDY SHEET:</p>
            <a href="{{ asset('storage/attachments/' . $request->attachment) }}" 
               target="_blank" 
               class="text-blue-500 dark:text-blue-400 hover:underline">
                📄 {{ $request->attachment }}
            </a>
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">No attachments available.</p>
    @endif
</div>

        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-5 flex flex-wrap gap-2">
            <a href="{{ route('staff.main', ['page' => request()->query('page', 1)]) }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
            @if (!str_contains($request->status, 'Approved by') && !str_contains($request->status, 'Rejected by'))
                <button id="editRequestButton" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Request
                </button>
            @endif
        </div>
</div>

<!-- Edit Request Modal -->
<div id="editRequestModal" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4">

        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Request
        </h2>

        <form id="editRequestForm" 
      action="{{ route('staff.requests.update', $request->id) }}" 
      method="POST" 
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <!-- Hidden ID and Part Number -->
    <input type="hidden" name="id" value="{{ $request->id }}">
    <input type="hidden" name="unique_code" value="{{ $request->unique_code }}">
    <input type="hidden" name="part_number" value="{{ $request->part_number }}">

    <!-- ✅ Ensure is_edited is included -->
    <input type="hidden" name="is_edited" value="1">

    <div class="space-y-4">
        <div>
            <label for="edit-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <input 
                type="text" 
                name="description" 
                id="edit-description" 
                value="{{ $request->description }}" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
            >
        </div>

        <div>
            <label for="edit-part-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Part Name</label>
            <input 
                type="text" 
                name="part_name" 
                id="edit-part-name" 
                value="{{ old('part_name', $request->part_name) }}" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                required
            >
        </div>

        <!-- Attachment -->
        <div>
            <label for="edit-attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment</label>
            <input 
                type="file" 
                name="attachment" 
                id="edit-attachment" 
                class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200"
            >
            @if ($request->attachment)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Current Attachment: 
                    <a href="{{ asset('storage/' . $request->attachment) }}" 
                       target="_blank" 
                       class="text-blue-500 hover:underline">Download</a>
                </p>
            @endif
        </div>
    </div>

    <div class="mt-6 flex justify-end space-x-2">
        <button type="submit" 
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Update Request
        </button>
    </div>
</form>

    </div>
</div>
</div>
<!-- Pusher Script for Real-Time Updates -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ✅ Open the modal when "Edit Request" button is clicked
        const editBtn = document.getElementById('editRequestButton');
        const editModal = document.getElementById('editRequestModal');
        const cancelBtn = document.getElementById('cancelEditModal');

        editBtn.addEventListener('click', () => {
            editModal.classList.remove('hidden');
        });

        // ✅ Close the modal when "Cancel" button is clicked
        cancelBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        // ✅ Close modal when clicking outside the modal
        editModal.addEventListener('click', (event) => {
            if (event.target === editModal) {
                editModal.classList.add('hidden');
            }
        });

        // ✅ Handle attachment removal
        const removeBtn = document.getElementById('remove-attachment-btn');
        const removeInput = document.getElementById('remove-attachment-input');

        if (removeBtn && removeInput) {
            removeBtn.addEventListener('click', () => {
                if (confirm('Are you sure you want to remove the attachment?')) {
                    removeInput.value = '1';  // Mark attachment for removal
                    removeBtn.closest('p').remove();  // Remove the attachment display
                }
            });
        }

    });
    document.getElementById('editRequestForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let formData = new FormData(this);
    formData.append('_method', 'PUT'); 

    // 🚀 Log form data before submission
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

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
            window.location.reload();
        } else {
            console.error('Update failed:', data);
            alert(`Failed to update the request: ${data.error}`);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('An error occurred. Please check the console for details.');
    });
});

</script>

@endsection