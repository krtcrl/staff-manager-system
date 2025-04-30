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
                Initial Request Details
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
        <p class="text-gray-500 dark:text-gray-400">Click to download Attachment:</p>
        <a href="#" 
           onclick="downloadAttachment('{{ route('staff.download.attachment', ['filename' => $request->attachment]) }}')"
           class="text-blue-500 dark:text-blue-400 hover:underline flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {{ $request->attachment }}
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
            <a href="{{ route('staff.prelist', ['page' => request()->query('page', 1)]) }}" 
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
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
     onclick="closeModal(event)">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4"
         onclick="event.stopPropagation()">

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
            <input type="hidden" name="is_edited" value="1">

            <div class="space-y-4">
                <div>
                    <label for="edit-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (Optional)</label>
                    <input 
                        type="text" 
                        name="description" 
                        id="edit-description" 
                        value="{{ $request->description }}" 
                        class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                </div>

                <!-- Attachment -->
                <div>
                    <label for="edit-attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment (Excel files only)</label>
                    <input 
                        type="file" 
                        name="attachment" 
                        id="edit-attachment" 
                        accept=".xls,.xlsx,.xlsb"  
                        class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
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
                <button type="button" 
                        onclick="closeModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Cancel Edit
                </button>
            </div>
        </form>
    </div>
</div>




</div>
<!-- Pusher Script for Real-Time Updates -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    function closeModal(event) {
        if (!event || event.target.id === 'editRequestModal') {
            document.getElementById('editRequestModal').classList.add('hidden');
        }
    }

    function downloadAttachment(url) {
        // Try the anchor method first
        try {
            const anchor = document.createElement('a');
            anchor.style.display = 'none';
            anchor.href = url;
            anchor.download = '';
            
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
            
            // If anchor method works, return and don't use iframe fallback
            return;
        } catch (e) {
            console.log('Anchor method failed, falling back to iframe');
        }
        
        // Only use iframe if anchor method fails
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);
        setTimeout(() => document.body.removeChild(iframe), 10000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // [Rest of your existing code remains exactly the same]
        // Open the modal when "Edit Request" button is clicked
        const editBtn = document.getElementById('editRequestButton');
        const editModal = document.getElementById('editRequestModal');
        const cancelBtn = document.getElementById('cancelEditModal');

        if (editBtn) {
            editBtn.addEventListener('click', () => {
                editModal.classList.remove('hidden');
            });
        }

        // Close the modal when "Cancel" button is clicked
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                editModal.classList.add('hidden');
            });
        }

        // Handle attachment removal
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

        // Handle attachment downloads
        const downloadLinks = document.querySelectorAll('a[data-download-attachment]');
        downloadLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                downloadAttachment(this.href);
            });
        });

        // Form submission handling
        const editForm = document.getElementById('editRequestForm');
        if (editForm) {
            editForm.addEventListener('submit', function (event) {
                event.preventDefault();

                let formData = new FormData(this);
                formData.append('_method', 'PUT'); 

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
                        alert(`Failed to update the request: ${data.error || 'Unknown error'}`);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred. Please check the console for details.');
                });
            });
        }
    });
</script>
@endsection