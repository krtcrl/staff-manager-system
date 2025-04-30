@extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">
       <!-- Final Request Details Card -->
<div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-xl shadow-md hover:shadow-lg border border-gray-300 dark:border-gray-600 transition duration-300 ease-in-out">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-3">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                Final Approval Details
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Part Number: <span class="font-medium">{{ $finalRequest->part_number }}</span>
            </p>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded">
            Created: <span id="created-time">{{ $finalRequest->created_at->format('M j, Y, g:i A') }}</span>
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
                        <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $finalRequest->part_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                        <p class="text-gray-800 dark:text-gray-300">{{ $finalRequest->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Process Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Process Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Status</p>
                        @if(str_contains($finalRequest->status, 'Approved by'))
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                {{ $finalRequest->status }}
                            </div>
                        @elseif(str_contains($finalRequest->status, 'Rejected by'))
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                {{ $finalRequest->status }}
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                Pending
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-3">
            <!-- Status Overview -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Manager Approvals</h3>
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

            <!-- Attachment Section -->
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
                        <p class="text-gray-500 dark:text-gray-400">FINAL APPROVAL FORM:</p>
                        <a href="#" 
                           onclick="downloadAttachment('{{ route('download.attachment', ['filename' => rawurlencode($request->attachment)]) }}')"
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
        <a href="{{ route('staff.finallist') }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>

        @if (!str_contains($finalRequest->status, 'Approved by') && !str_contains($finalRequest->status, 'Rejected by'))
            <button id="editFinalRequestButton" 
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Final Request
            </button>
        @endif
    </div>

    <!-- Edit Final Request Modal -->
    <div id="editFinalRequestModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
         onclick="closeModal(event)">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4"
             onclick="event.stopPropagation()">

            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Final Request
            </h2>

            <form id="editFinalRequestForm" 
                  action="{{ route('staff.finalRequests.update', $finalRequest->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <!-- Hidden ID and Part Number -->
                <input type="hidden" name="id" value="{{ $finalRequest->id }}">
                <input type="hidden" name="unique_code" value="{{ $finalRequest->unique_code }}">
                <input type="hidden" name="part_number" value="{{ $finalRequest->part_number }}">
                <input type="hidden" name="is_edited" value="1">

                <div class="space-y-4">
                    <div>
                        <label for="edit-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (Optional)</label>
                        <input 
                            type="text" 
                            name="description" 
                            id="edit-description" 
                            value="{{ $finalRequest->description }}" 
                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    </div>

                    <!-- Attachment Section -->
                    <div>
                        <label for="edit-final-approval-attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachment (Excel files only)</label>
                        <input 
                            type="file" 
                            name="attachment" 
                            id="edit-attachment" 
                            accept=".xls,.xlsx,.xlsb"  
                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200">

                        @if ($request->attachment)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                Current Attachment: 
                                <a href="#" 
                                   onclick="downloadAttachment('{{ route('download.attachment', ['filename' => rawurlencode($request->attachment)]) }}')"
                                   class="text-blue-500 hover:underline">Download</a>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Update Final Request
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

<script>
    // Silent download function for final attachments
    function downloadAttachment(url) {
        // Create a temporary anchor element
        const anchor = document.createElement('a');
        anchor.style.display = 'none';
        anchor.href = url;
        anchor.download = '';
        
        // Append to body, trigger click, then remove
        document.body.appendChild(anchor);
        anchor.click();
        document.body.removeChild(anchor);
    }

    // Timezone conversion
    window.addEventListener('DOMContentLoaded', () => {
        const createdTime = document.getElementById('created-time');
        if (createdTime) {
            const utcTime = "{{ $finalRequest->created_at->format('Y-m-d H:i:s') }}";
            const date = new Date(utcTime + ' UTC');
            
            const options = {
                timeZone: 'Asia/Singapore', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true
            };

            createdTime.textContent = date.toLocaleString('en-US', options);
        }
    });

    // Edit Modal Script
    document.addEventListener('DOMContentLoaded', () => {
        const editBtn = document.getElementById('editFinalRequestButton');
        const editModal = document.getElementById('editFinalRequestModal');
        
        // Open Modal
        if (editBtn) {
            editBtn.addEventListener('click', () => {
                editModal.classList.remove('hidden');
            });
        }

        // Close Modal when clicking outside
        editModal.addEventListener('click', (event) => {
            if (event.target === editModal) {
                closeModal();
            }
        });
    });

    // Close Modal function
    function closeModal() {
        const editModal = document.getElementById('editFinalRequestModal');
        editModal.classList.add('hidden');
    }

    // Form submission handling
    const editForm = document.getElementById('editFinalRequestForm');
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
                    alert('Final request updated successfully!');
                    window.location.reload();
                } else {
                    console.error('Update failed:', data);
                    alert(`Failed to update the final request: ${data.error || 'Unknown error'}`);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred. Please check the console for details.');
            });
        });
    }
</script>

@endsection