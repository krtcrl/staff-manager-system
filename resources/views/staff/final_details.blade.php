@extends('layouts.staff')

@section('content')
<div class="h-screen flex flex-col overflow-hidden bg-gray-100 dark:bg-gray-900"> <!-- Dark mode -->
    <div class="flex-1 overflow-y-auto p-4">
        <div class="flex flex-col lg:flex-row gap-4">

            <!-- Left Column: Final Request Details -->
            <div class="w-full lg:w-1/2 flex flex-col">
                <h1 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-300">Final Request Details</h1> <!-- Dark mode -->

                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm flex flex-col flex-grow"> <!-- Dark mode -->
                    <div class="flex-grow">
                        <p class="mb-2 text-gray-800 dark:text-gray-300">Details for the final request with Unique Code: 
                            <span class="font-semibold">{{ $finalRequest->unique_code }}</span>.
                        </p>

                        <div class="space-y-3 text-sm text-gray-800 dark:text-gray-300"> <!-- Dark mode -->
                            <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                            <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                            <div><span class="font-semibold">Part Name:</span> {{ $finalRequest->part_name }}</div>

                            <!-- ✅ Updated UPH to Bottle Neck UPH -->
                            <div><span class="font-semibold">Bottle Neck UPH:</span> {{ $finalRequest->bottle_neck_uph }}</div>

                            <div class="border-t pt-3 mt-3">
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Yield Information</h2>
                                <div><span class="font-semibold">Standard Yield Percentage:</span> {{ $finalRequest->standard_yield_percentage }}%</div>
                                <div><span class="font-semibold">Standard Yield $/Hour:</span> ${{ $finalRequest->standard_yield_dollar_per_hour }}</div>
                                <div><span class="font-semibold">Actual Yield Percentage:</span> {{ $finalRequest->actual_yield_percentage }}%</div>
                                <div><span class="font-semibold">Actual Yield $/Hour:</span> ${{ $finalRequest->actual_yield_dollar_per_hour }}</div>
                            </div>

                            <div class="border-t pt-3 mt-3">
                                <span class="font-semibold">Created:</span> 
                                <span id="created-time">
                                    {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Back to List Button -->
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('staff.finallist') }}" 
                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Final Approval Attachment -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm h-[calc(100vh-10rem)]"> <!-- Dark mode -->
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Final Approval Attachment</h2>

                        @if ($finalRequest->final_approval_attachment)
                            <button id="fullscreen-btn" 
                                class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                Full Screen
                            </button>
                        @endif
                    </div>

                    @if ($finalRequest->final_approval_attachment)
                        <div id="attachment-container" 
                             class="h-[calc(100%-3rem)] overflow-y-auto border border-gray-200 rounded-lg p-2 relative">
                            <iframe 
                                id="attachment-iframe"
                                src="{{ asset('storage/' . $finalRequest->final_approval_attachment) }}" 
                                class="w-full h-full border rounded-lg">
                            </iframe>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">No final approval attachment available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timezone Conversion Script -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        const createdTime = document.getElementById('created-time');
        if (createdTime) {
            const utcTime = "{{ $finalRequest->created_at->format('Y-m-d H:i:s') }}";
            const date = new Date(utcTime + ' UTC');
            
            // Convert to GMT+8
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
</script>
@endsection
