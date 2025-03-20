@extends('layouts.staff')

@section('content')
<div class="h-screen flex flex-col overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4">
        <div class="flex flex-col lg:flex-row gap-4">
            
            <!-- Left Column: Final Request Details -->
            <div class="w-full lg:w-1/2 flex flex-col">
                <h1 class="text-2xl font-semibold mb-4">Final Request Details</h1>

                <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow">
                    <div class="flex-grow">
                        <p class="mb-2">Details for the final request with Unique Code: 
                            <span class="font-semibold">{{ $finalRequest->unique_code }}</span>.
                        </p>

                        <div class="space-y-3 text-sm">
                            <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                            <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                            <div><span class="font-semibold">Part Name:</span> {{ $finalRequest->part_name }}</div>
                            <div><span class="font-semibold">UPH:</span> {{ $finalRequest->uph }}</div>

                            <div class="border-t pt-3 mt-3">
                                <h2 class="text-lg font-semibold text-gray-700">Yield Information</h2>
                                <div><span class="font-semibold">Standard Yield Percentage:</span> {{ $finalRequest->standard_yield_percentage }}%</div>
                                <div><span class="font-semibold">Standard Yield $/Hour:</span> ${{ $finalRequest->standard_yield_dollar_per_hour }}</div>
                                <div><span class="font-semibold">Actual Yield Percentage:</span> {{ $finalRequest->actual_yield_percentage }}%</div>
                                <div><span class="font-semibold">Actual Yield $/Hour:</span> ${{ $finalRequest->actual_yield_dollar_per_hour }}</div>
                            </div>

                            <div class="border-t pt-3 mt-3">
                                <span class="font-semibold">Created:</span> {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
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

            <!-- Right Column: Attachment -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white p-4 rounded-lg shadow-sm h-[calc(100vh-10rem)]">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-semibold text-gray-700">Attachment</h2>
                        @if ($finalRequest->attachment)
                            <button id="fullscreen-btn" 
                                class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                                Full Screen
                            </button>
                        @endif
                    </div>

                    @if ($finalRequest->attachment)
                        <div id="attachment-container" 
                             class="h-[calc(100%-3rem)] overflow-y-auto border border-gray-200 rounded-lg p-2 relative">
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
@endsection
