@extends('layouts.manager')

@section('content')
    <div class="h-screen flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Column: Page Title and Request Details -->
                <div class="w-full lg:w-1/2 flex flex-col">
                    <h1 class="text-2xl font-semibold mb-4">Request Details</h1>

                    <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow">
                        <div class="flex-grow">
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
                                <div class="mb-4 flex space-x-2">
                                    <!-- Approve Button -->
                                    <form action="{{ route('manager.request.approve', $request->unique_code) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                            Approve Request
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <button id="reject-button" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                        Reject Request
                                    </button>
                                </div>

                                <!-- Rejection Form (Initially Hidden) -->
                                <div id="reject-form" class="hidden bg-gray-100 p-4 rounded shadow-md">
                                    <form id="reject-form-submit" action="{{ route('manager.request.reject', $request->unique_code) }}" method="POST">
                                        @csrf
                                        <label class="block text-gray-700 font-semibold">Rejection Reason:</label>
                                        <textarea name="rejection_reason" rows="3" class="w-full p-2 border rounded mt-1" required></textarea>

                                        <button type="submit" class="mt-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            Submit Rejection
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="mb-2 p-2 bg-green-100 border border-green-400 text-green-700 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="space-y-2">
                                <div><span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}</div>
                                <div><span class="font-semibold">Part Number:</span> {{ $request->part_number }}</div>
                                <div><span class="font-semibold">Part Name:</span> {{ $request->part_name }}</div>
                                <div><span class="font-semibold">Description:</span> {{ $request->description }}</div>
                                <div><span class="font-semibold">Revision:</span> {{ $request->revision_type }}</div>
                                <div>
                                    <span class="font-semibold">Status:</span>
                                    @if ($status === 'approved')
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($status === 'rejected')
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500 font-semibold">Pending</span>
                                    @endif
                                </div>
                                <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}</div>
                            </div>
                        </div>

                        <!-- Back to List Button at the Bottom -->
                        <div class="mt-4">
                        <a href="{{ route('manager.request-list', ['page' => request()->query('page', 1)]) }}" 
                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Back to List
                        </a>
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