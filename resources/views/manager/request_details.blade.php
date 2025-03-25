@extends('layouts.manager')

@section('content')
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
        <!-- Request Details Section at the Top -->
        <div class="bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg ring-2 ring-blue-500 ring-offset-2 mb-2">
        <!-- Title and Created At Timestamp -->
            <div class="flex justify-between items-start mb-1">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">
                    Pre Approval Details
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
                <div>
        <strong>Current Process Type:</strong> {{ $processType }}
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

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg ring-2 ring-blue-500 ring-offset-2 mt-4 border border-gray-100 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-5 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
        </svg>
        Attachment (Process Study Sheet)
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