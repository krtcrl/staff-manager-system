@extends('layouts.manager')

@section('content')
@php
    $managerNumber = Auth::guard('manager')->user()->manager_number;  
    $statusColumn = 'manager_' . $managerNumber . '_status';

    // Get the manager's status
    $status = $request->$statusColumn ?? $finalRequest->$statusColumn ?? 'pending';

    // Check if the request is edited
    $isEdited = $request?->is_edited ?? $finalRequest?->is_edited ?? false;

    // Button visibility logic: Hide if status is 'approved', 'rejected', or if request is not edited
    $hideButtons = !$isEdited || ($status === 'approved' || $status === 'rejected');

    // Sequential approval logic: Ensure all previous managers have approved
    $showButtons = true;
    for ($i = 1; $i < $managerNumber; $i++) {
        $prevStatusColumn = 'manager_' . $i . '_status';
        $prevStatus = $request->$prevStatusColumn ?? $finalRequest->$prevStatusColumn ?? null;

        // If any previous manager hasn't approved, hide the buttons
        if ($prevStatus !== 'approved') {
            $showButtons = false;
            break;
        }
    }
@endphp


<!-- Notification Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/></svg>
        </span>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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
                        <p class="text-gray-800 dark:text-gray-300">{!! nl2br(e($request->description)) !!}</p>
                    </div>
                </div>
            </div>

            <!-- Process Information -->
            <div class="bg-white dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Process Information</h3>
                <div class="space-y-2">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Process Type</p>
                        <p class="text-gray-800 dark:text-gray-300 font-medium">{{ $processType }}</p>
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
    <p class="text-sm text-gray-500 dark:text-gray-400">Your Status</p>

    @php
        // Get current manager number
        $managerNumber = Auth::guard('manager')->user()->manager_number;
        $statusColumn = 'manager_' . $managerNumber . '_status';

        // Check status from both tables
        $status = $request->$statusColumn ?? $finalRequest->$statusColumn ?? 'pending';
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
                @if ($request->attachment)
                    <div class="text-sm">
                        <p class="text-gray-500 dark:text-gray-400">PROCESS STUDY SHEET:</p>
                        <a href="{{ route('manager.download.attachment', ['filename' => rawurlencode($request->attachment)]) }}" 
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

<!-- Back to List -->
<a href="{{ route('manager.request-list', ['page' => request()->query('page', 1)]) }}" 
   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
    Back to List
</a>

<!-- Approve & Reject Buttons -->
@if (!$hideButtons && $showButtons)
    <!-- Approve Button -->
    <form action="{{ route('manager.request.approve', $request->unique_code) }}" method="POST">
        @csrf
        <button type="submit" 
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
            Approve Request
        </button>
    </form>

    <!-- Reject Button -->
    <button id="reject-button" 
            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
        Reject Request
    </button>
@endif
</div>

<!-- Reject Form (Initially Hidden) -->
@if (!$hideButtons && $showButtons)
<div id="reject-form" 
 class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">

<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full border border-gray-300 dark:border-gray-700">
    <form action="{{ route('manager.request.reject', ['unique_code' => $request->unique_code]) }}" method="POST">
        @csrf
        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Rejection Reason:
        </label>
        <textarea name="rejection_reason" id="rejection_reason" placeholder="Enter reason"
                  class="w-full p-3 border rounded-lg mt-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300"
                  rows="4"></textarea>

        <div class="flex justify-end mt-4 gap-2">
            <button type="button" id="cancel-button" 
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                Cancel
            </button>

            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                Submit Rejection
            </button>
        </div>
    </form>
</div>
</div>
@endif
<!-- JavaScript for Toggle -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const rejectButton = document.getElementById("reject-button");
        const rejectForm = document.getElementById("reject-form");
        const cancelButton = document.getElementById("cancel-button");

        if (rejectButton && rejectForm && cancelButton) {
            // Toggle the reject form
            rejectButton.addEventListener("click", () => {
                rejectForm.classList.toggle("hidden");
            });

            // Cancel button hides the form
            cancelButton.addEventListener("click", () => {
                rejectForm.classList.add("hidden");
            });
        }
    });
</script>



</div>


<!-- Script for Rejection Form and Fullscreen -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Automatically close success/error messages after 5 seconds
    setTimeout(() => {
        let successMessage = document.querySelector('.bg-green-100');
        let errorMessage = document.querySelector('.bg-red-100');

        if (successMessage) {
            successMessage.remove();
        }

        if (errorMessage) {
            errorMessage.remove();
        }
    }, 5000); // 5000 milliseconds = 5 seconds
});
    
        // Fullscreen functionality
        let fullscreenButton = document.getElementById("fullscreen-btn");
        if (fullscreenButton) {
            fullscreenButton.addEventListener("click", function () {
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
        }

        // Check if the success message indicates the request was fully approved
        let successMessage = document.querySelector('.bg-green-100');
        if (successMessage && successMessage.textContent.includes('fully approved')) {
            // Refresh the page after 2 seconds
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    });
    document.addEventListener('DOMContentLoaded', () => {
    const rejectButton = document.getElementById('reject-button');
    const rejectForm = document.getElementById('reject-form');

    if (rejectButton && rejectForm) {
        rejectButton.addEventListener('click', () => {
            rejectForm.classList.toggle('hidden');
        });
    }
});
</script>
@endsection