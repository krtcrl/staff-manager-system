@extends('layouts.manager')

@section('content')
@php
    $managerNumber = Auth::guard('manager')->user()->manager_number;

    // Manager-to-status mapping
    $managerToStatusMapping = [
        1 => 'manager_1_status',
        5 => 'manager_2_status',
        6 => 'manager_3_status',
        7 => 'manager_4_status',
        8 => 'manager_5_status',
        9 => 'manager_6_status',
    ];

    // Map the manager number to the correct status column
    $statusColumn = $managerToStatusMapping[$managerNumber] ?? null;

    // Safely retrieve the current manager's status
    $status = $statusColumn ? ($finalRequest->$statusColumn ?? 'pending') : 'pending';

    // Check if the final manager has approved the request (final manager's status)
    $finalManagerStatus = $finalRequest->manager_6_status ?? 'pending'; // Assuming Manager 6 is the final manager

    // Hide buttons if the final manager has approved the request
    $showButtons = ($status === 'pending' || $finalManagerStatus === 'approved') && $status !== 'rejected';

    // Ensure previous managers' statuses are approved
    foreach ($managerToStatusMapping as $managerNum => $column) {
        if ($managerNum >= $managerNumber) break; // Only check previous managers

        $prevStatus = $finalRequest->$column ?? null;

        if ($prevStatus !== 'approved') {
            $showButtons = false;
            break;
        }
    }
@endphp

<!-- Notification Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert" id="success-message">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/></svg>
        </span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-opacity duration-500" role="alert" id="error-message">
        <span class="block sm:inline">{{ session('error') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/></svg>
        </span>
    </div>
@endif

<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">
       <!-- Request Details Card -->
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
                        <p class="text-gray-800 dark:text-gray-300">{!! nl2br(e($finalRequest->description)) !!}</p>
                    </div>
                </div>
            </div>

            <!-- Process Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Process Information</h3>
                <div class="space-y-2">
                <div>
    <p class="text-sm text-gray-500 dark:text-gray-400">Your Status</p>

    @php
        // Get current manager number
        $managerNumber = Auth::guard('manager')->user()->manager_number;

        // ✅ Manager-to-status mapping
        $managerToStatusMapping = [
            1 => 'manager_1_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status',
        ];

        // ✅ Safely map the manager number to the status column
        $statusColumn = $managerToStatusMapping[$managerNumber] ?? null;

        // ✅ Get the status from the FinalRequest table
        $status = $statusColumn ? ($finalRequest->$statusColumn ?? 'pending') : 'pending';
    @endphp

    @if ($status === 'approved')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
            Approved
        </span>
    @elseif ($status === 'rejected')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
            Rejected
        </span>
    @else
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
            Pending
        </span>
    @endif
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
                    <!-- Manager Approvals -->
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Manager Approvals</p>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-green-50 dark:bg-green-900/30 p-2 rounded-lg">
                                <span class="text-green-600 dark:text-green-400 font-bold text-lg">{{ count($approvedManagers) }}</span>
                                <p class="text-xs text-green-600 dark:text-green-400">Approved</p>
                            </div>

                            <div class="bg-red-50 dark:bg-red-900/30 p-2 rounded-lg">
                                <span class="text-red-600 dark:text-red-400 font-bold text-lg">{{ count($rejectedManagers) }}</span>
                                <p class="text-xs text-red-600 dark:text-red-400">Rejected</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <span class="text-gray-600 dark:text-gray-400 font-bold text-lg">{{ count($pendingManagers) }}</span>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attachment Section -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments</h3>
                @if ($finalRequest->final_approval_attachment)
                    <div class="text-sm">
                        <p class="text-gray-500 dark:text-gray-400">Click to download Attachment:</p>
                        <a href="#" 
                           onclick="downloadAttachment('{{ route('manager.download.final-attachment', ['filename' => rawurlencode($finalRequest->final_approval_attachment)]) }}')"
                           class="text-blue-500 dark:text-blue-400 hover:underline flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ $finalRequest->final_approval_attachment }}
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No attachments available.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="mt-5 bg-white dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
        <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Update Request</h3>
        <form id="update-form" action="{{ route('manager.finalrequest.update', $finalRequest->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                    placeholder="Update description">{{ old('description', $finalRequest->description) }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="final_approval_attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Update Final Approval Attachment
                </label>
                <input type="file" name="final_approval_attachment" id="final_approval_attachment"
                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-300
                    hover:file:bg-blue-100 dark:hover:file:bg-blue-900/40
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">XLS, XLSX, DOCX, or PDF (Max: 2MB)</p>
                
                @if ($finalRequest->final_approval_attachment)
                    <div class="mt-2 flex items-center">
                        <input type="checkbox" name="remove_final_approval_attachment" id="remove_final_approval_attachment" class="mr-2">
                        <label for="remove_final_approval_attachment" class="text-sm text-gray-700 dark:text-gray-300">Remove current attachment</label>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Request
                </button>
            </div>
        </form>
    </div>

    <div class="mt-5 flex flex-wrap gap-2">
        <!-- Back to List with Icon -->
        <a href="{{ route('manager.finalrequest-list', ['page' => request()->query('page', 1)]) }}" 
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>

        @if ($showButtons)
            <!-- Approve Button with Icon - Updated with fixed size loading -->
            <form id="approve-form" action="{{ route('manager.finalrequest.approve', $finalRequest->unique_code) }}" method="POST">
                @csrf
                <button type="submit" id="approve-button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-1 justify-center min-w-[120px] h-[36px]">
                    <span id="approve-content" class="flex items-center gap-1">
                        <svg id="approve-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="approve-text">Approve Request</span>
                    </span>
                    <span id="approve-spinner" class="hidden absolute">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Reject Button with Icon -->
            <button id="reject-button" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-1 min-w-[120px] h-[36px] justify-center">
                <svg id="reject-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span id="reject-text">Reject Request</span>
            </button>
        @endif
    </div>

    <!-- Reject Form (Initially Hidden) -->
    @if ($showButtons)
    <div id="reject-form" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full border border-gray-300 dark:border-gray-700">
            <form id="reject-form-submit" action="{{ route('manager.finalrequest.reject', ['unique_code' => $finalRequest->unique_code]) }}" method="POST">
                @csrf
                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Rejection Reason:
                </label>
                <textarea name="rejection_reason" id="rejection_reason" placeholder="Enter reason"
                          class="w-full p-3 border rounded-lg mt-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                          rows="4" required></textarea>

                <div class="flex justify-end mt-4 gap-2">
                    <button type="button" id="cancel-button" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        Cancel
                    </button>

                    <button type="submit" id="submit-reject-button" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition flex items-center gap-1 min-w-[120px] h-[36px] justify-center">
                        <svg id="submit-reject-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span id="submit-reject-text">Submit Rejection</span>
                        <span id="submit-reject-spinner" class="hidden absolute">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
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

    document.addEventListener("DOMContentLoaded", function () {
        const rejectButton = document.getElementById("reject-button");
        const rejectForm = document.getElementById("reject-form");
        const cancelButton = document.getElementById("cancel-button");
        const approveForm = document.getElementById("approve-form");
        const rejectFormSubmit = document.getElementById("reject-form-submit");
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        const updateForm = document.getElementById('update-form');

        // Toggle reject form
        if (rejectButton && rejectForm) {
            rejectButton.addEventListener("click", () => {
                rejectForm.classList.toggle("hidden");
            });
        }

        // Hide reject form on cancel
        if (cancelButton) {
            cancelButton.addEventListener("click", () => {
                rejectForm.classList.add("hidden");
            });
        }

        // Prevent form double submission for approve
        if (approveForm) {
            approveForm.addEventListener('submit', function(e) {
                if (approveForm.checkValidity()) {
                    const button = document.getElementById('approve-button');
                    const content = document.getElementById('approve-content');
                    const spinner = document.getElementById('approve-spinner');
                    
                    button.disabled = true;
                    button.classList.add('opacity-75', 'cursor-not-allowed');
                    content.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    spinner.classList.add('flex', 'items-center', 'justify-center');
                }
            });
        }

        // Prevent form double submission for reject
        if (rejectFormSubmit) {
            rejectFormSubmit.addEventListener('submit', function(e) {
                if (rejectFormSubmit.checkValidity()) {
                    const button = document.getElementById('submit-reject-button');
                    const icon = document.getElementById('submit-reject-icon');
                    const text = document.getElementById('submit-reject-text');
                    const spinner = document.getElementById('submit-reject-spinner');
                    
                    button.disabled = true;
                    button.classList.add('opacity-75', 'cursor-not-allowed');
                    icon.classList.add('hidden');
                    text.classList.add('hidden');
                    spinner.classList.remove('hidden');
                    spinner.classList.add('flex', 'items-center', 'justify-center');
                }
            });
        }

        // Prevent form double submission for update
        if (updateForm) {
            updateForm.addEventListener('submit', function(e) {
                if (updateForm.checkValidity()) {
                    const button = updateForm.querySelector('button[type="submit"]');
                    button.disabled = true;
                    button.classList.add('opacity-75', 'cursor-not-allowed');
                    
                    // Add spinner to button
                    const spinner = document.createElement('span');
                    spinner.className = 'ml-2 animate-spin h-4 w-4 text-white';
                    spinner.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    `;
                    button.appendChild(spinner);
                }
            });
        }

        // Auto-dismiss notifications after 5 seconds with fade effect
        function fadeOut(element) {
            if (element) {
                element.style.opacity = '1';
                
                setTimeout(() => {
                    element.style.transition = 'opacity 500ms';
                    element.style.opacity = '0';
                    
                    setTimeout(() => {
                        element.style.display = 'none';
                    }, 500);
                }, 3000); // Start fading after 3 seconds
            }
        }
        
        fadeOut(successMessage);
        fadeOut(errorMessage);
        
        // Check if the success message indicates the request was fully approved
        if (successMessage && successMessage.textContent.includes('fully approved')) {
            // Fade out the page before refresh
            setTimeout(() => {
                document.body.style.transition = 'opacity 500ms';
                document.body.style.opacity = '0';
                
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }, 1500); // Start fade out after 1.5 seconds, refresh after 2 seconds total
        }
    });
</script>
@endsection