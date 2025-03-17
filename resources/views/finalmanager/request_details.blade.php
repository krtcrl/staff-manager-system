@extends('layouts.finalmanager')

@section('content')
    <div class="h-screen flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Column: Page Title and Request Details -->
                <div class="w-full lg:w-1/2 flex flex-col">
                    <h1 class="text-2xl font-semibold mb-4">Request Details</h1>

                    <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow">
                        <div class="flex-grow">
                            <p class="mb-2">Here are the details for the request with Unique Code: 
                                <span class="font-semibold">{{ $finalRequest->unique_code }}</span>.
                            </p>

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
                                <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                                <div><span class="font-semibold">Description:</span> {{ $finalRequest->description }}</div>
                                <div><span class="font-semibold">Revision:</span> {{ $finalRequest->revision_type }}</div>

                                <div>
                                    <span class="font-semibold">Status:</span>
                                    @if ($finalRequest->overall_status === 'completed')
                                        <span class="text-green-500 font-semibold">Completed</span>
                                    @else
                                        <span class="text-gray-500 font-semibold">Pending</span>
                                    @endif
                                </div>
                                <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                                <div><span class="font-semibold">Part Name:</span> {{ $finalRequest->part_name }}</div>
                                <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $finalRequest->uph }}</div>
                            </div>
                        </div>

                        <!-- Back to List Button at the Bottom -->
                        <div class="mt-4">
                            <a href="{{ route('finalmanager.dashboard') }}" 
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
                            @if ($finalRequest->attachment)
                                <button id="fullscreen-btn" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                    Full Screen
                                </button>
                            @endif
                        </div>

                        @if ($finalRequest->attachment)
                            <div id="attachment-container" class="h-[calc(100%-3rem)] overflow-y-auto border border-gray-200 rounded-lg p-2 relative">
                                <iframe 
                                    id="attachment-iframe"
                                    src="{{ asset('storage/' . $finalRequest->attachment) }}" 
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
        // Fullscreen functionality
        document.getElementById("fullscreen-btn")?.addEventListener("click", function () {
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