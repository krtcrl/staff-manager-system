@extends('layouts.manager')

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
                        <span class="text-gray-800 dark:text-gray-300">{!! nl2br(e($request->description)) !!}</span>
                    </div>

                    <!-- Status -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Status:</span>
                        @php
                            $managerNumber = Auth::guard('manager')->user()->manager_number;
                            $statusColumn = 'manager_' . $managerNumber . '_status';
                            $status = $request->$statusColumn;
                        @endphp
                        @if ($status === 'approved')
                            <span class="text-green-500 font-semibold">Approved</span>
                        @elseif ($status === 'rejected')
                            <span class="text-red-500 font-semibold">Rejected</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400 font-semibold">Pending</span>
                        @endif
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-0.5">
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

            <!-- Back to List and Action Buttons -->
            <div class="mt-2 flex space-x-1">
                <a href="{{ route('manager.request-list', ['page' => request()->query('page', 1)]) }}" 
                   class="px-2 py-0.5 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Back to List
                </a>

                @php
                    $managerNumber = Auth::guard('manager')->user()->manager_number;
                    $statusColumn = 'manager_' . $managerNumber . '_status';
                    $status = $request->$statusColumn;

                    // Check if all previous managers have approved the request
                    $showButtons = true;
                    for ($i = 1; $i < $managerNumber; $i++) {
                        $prevStatusColumn = 'manager_' . $i . '_status';
                        if ($request->$prevStatusColumn !== 'approved') {
                            $showButtons = false;
                            break;
                        }
                    }

                    // Hide both approve and reject buttons if approved
                    $hideButtons = $status === 'approved';
                @endphp

                @if ($showButtons && !$hideButtons)
                    <!-- Approve Button -->
                    <form action="{{ route('manager.request.approve', $request->unique_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-2 py-0.5 bg-green-500 text-white rounded hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700">
                            Approve Request
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button id="reject-button" class="px-2 py-0.5 bg-red-500 text-white rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                        Reject Request
                    </button>
                @endif
            </div>

            <!-- Rejection Form (Initially Hidden) -->
            <div id="reject-form" class="hidden bg-gray-100 dark:bg-gray-700 p-4 rounded shadow-md mt-2">
                <form id="reject-form-submit" action="{{ route('manager.request.reject', $request->unique_code) }}" method="POST">
                    @csrf
                    <label class="block text-gray-700 dark:text-gray-300 font-semibold">Rejection Reason:</label>
                    <textarea name="rejection_reason" rows="3" class="w-full p-2 border rounded mt-1 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700" required></textarea>

                    <button type="submit" class="mt-2 px-2 py-0.5 bg-red-500 text-white rounded hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700">
                        Submit Rejection
                    </button>
                </form>
            </div>
        </div>

        <!-- Attachment Section with Bottom Padding -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Attachment</h2>
                @if ($request->attachment)
                    <button id="fullscreen-btn" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition dark:bg-gray-700 dark:hover:bg-gray-800">
                        Full Screen
                    </button>
                @endif
            </div>

            @if ($request->attachment)
                <!-- Added bottom margin and larger padding -->
                <div id="attachment-container" class="h-[600px] overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-12">
                    <iframe 
                        id="attachment-iframe"
                        src="{{ asset('storage/' . $request->attachment) }}" 
                        class="w-full h-full border rounded-lg">
                    </iframe>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No attachment available.</p>
            @endif
        </div>
    </div>
</div>

<!-- Script for Rejection Form and Fullscreen -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let rejectButton = document.getElementById("reject-button");
        let rejectForm = document.getElementById("reject-form");
        let rejectSubmitForm = document.querySelector("#reject-form form");

        if (rejectButton && rejectForm && rejectSubmitForm) {
            // Hide rejection form on page load
            rejectForm.classList.add("hidden");

            // Show rejection form when clicking "Reject Request"
            rejectButton.addEventListener("click", function () {
                rejectForm.classList.toggle("hidden");
            });

            // Close rejection modal after submitting
            rejectSubmitForm.addEventListener("submit", function () {
                setTimeout(() => {
                    rejectForm.classList.add("hidden");
                }, 500);
            });
        }

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
</script>
@endsection