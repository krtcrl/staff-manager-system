@extends('layouts.manager')

@section('content')
<div class="h-screen flex flex-col overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4">
        <div class="flex flex-col lg:flex-row gap-4">

            <!-- Left Column: Final Request Details -->
            <div class="w-full lg:w-1/2 flex flex-col">
    <h1 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-300">Final Request Details</h1>

                <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow">
                    <div class="flex-grow">

                        @php
                            // Manager-to-status mapping
                            $managerMapping = [
                                1 => 'manager_1_status',
                                5 => 'manager_2_status',
                                6 => 'manager_3_status',
                                7 => 'manager_4_status',
                                8 => 'manager_5_status',
                                9 => 'manager_6_status',
                            ];

                            $managerNumber = Auth::guard('manager')->user()->manager_number;
                            $statusColumn = $managerMapping[$managerNumber] ?? null;
                            $status = $statusColumn ? $finalRequest->$statusColumn : 'pending';

                            // Check if all previous managers have approved the request
                            $showButtons = true;
                            foreach ($managerMapping as $key => $col) {
                                if ($key >= $managerNumber) break; // Only check previous managers
                                if ($finalRequest->$col !== 'approved') {
                                    $showButtons = false;
                                    break;
                                }
                            }

                            // Hide buttons if already approved by the current manager
                            $hideButtons = $status === 'approved';
                        @endphp

                        <!-- Action Buttons -->
                        @if ($showButtons && !$hideButtons)
                            <div class="mb-4 flex space-x-2">
                                <!-- Approve Button -->
                                <form action="{{ route('manager.finalrequest.approve', $finalRequest->unique_code) }}" method="POST">
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

                            <!-- Rejection Form -->
                            <div id="reject-form" class="hidden bg-gray-100 p-4 rounded shadow-md">
                                <form id="reject-form-submit" action="{{ route('manager.finalrequest.reject', $finalRequest->unique_code) }}" method="POST">
                                    @csrf
                                    <label class="block text-gray-700 font-semibold">Rejection Reason:</label>
                                    <textarea name="rejection_reason" rows="3" class="w-full p-2 border rounded mt-1" required></textarea>

                                    <button type="submit" class="mt-2 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                        Submit Rejection
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- Flash Messages -->
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

                        <!-- Request Information -->
                        <div class="space-y-3 text-sm">
                            <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                            <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                            <div><span class="font-semibold">Part Name:</span> {{ $finalRequest->part_name }}</div>

                            <!-- ✅ Updated UPH to Bottle Neck UPH -->
                            <div><span class="font-semibold">Bottle Neck UPH:</span> {{ $finalRequest->bottle_neck_uph }}</div>

                            <div class="border-t pt-3 mt-3">
                                <h2 class="text-lg font-semibold text-gray-700">Yield Information</h2>
                                <div><span class="font-semibold">Standard Yield Percentage:</span> {{ $finalRequest->standard_yield_percentage }}%</div>
                                <div><span class="font-semibold">Standard Yield $/Hour:</span> ${{ $finalRequest->standard_yield_dollar_per_hour }}</div>
                                <div><span class="font-semibold">Actual Yield Percentage:</span> {{ $finalRequest->actual_yield_percentage }}%</div>
                                <div><span class="font-semibold">Actual Yield $/Hour:</span> ${{ $finalRequest->actual_yield_dollar_per_hour }}</div>
                            </div>

                            <div class="border-t pt-3 mt-3">
                                <span class="font-semibold">Status:</span>
                                @if ($status === 'approved')
                                    <span class="text-green-500 font-semibold">Approved</span>
                                @elseif ($status === 'rejected')
                                    <span class="text-red-500 font-semibold">Rejected</span>
                                @else
                                    <span class="text-gray-500 font-semibold">Pending</span>
                                @endif
                            </div>

                            <div class="border-t pt-3 mt-3">
                                <span class="font-semibold">Created:</span> {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Back to List Button -->
                    <div class="mt-4">
                        <a href="{{ route('manager.finalrequest-list', ['page' => request()->query('page', 1)]) }}" 
                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Final Approval Attachment -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white p-4 rounded-lg shadow-sm h-[calc(100vh-10rem)]">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold text-gray-700">Final Approval Attachment</h2>

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
                        <p class="text-gray-500">No final approval attachment available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const rejectButton = document.getElementById("reject-button");
    const rejectForm = document.getElementById("reject-form");

    rejectButton?.addEventListener("click", () => {
        rejectForm.classList.toggle("hidden");
    });
});
</script>
@endsection
