@extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">
        
        <!-- Final Request Details Section -->
        <div class="bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg ring-2 ring-blue-500 ring-offset-2 mb-2">
        <!-- Title and Created At Timestamp -->
            <div class="flex justify-between items-start mb-2">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">Final Approval Details</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Created: <span>{{ $finalRequest->created_at->format('M j, Y, g:i A') }}</span>
                </p>
            </div>

            <!-- Part Number Header -->
            <div class="mb-3">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-300">{{ $finalRequest->part_number }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Part Number</p>
            </div>

            <!-- Two Columns for Request Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <!-- Left Column -->
                <div class="space-y-1">
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Unique Code:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->unique_code }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Part Name:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->part_name }}</span>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-1">
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Status:</span>
                        @if(str_contains($finalRequest->status, 'Approved by'))
                            <span class="text-green-500 font-semibold">{{ $finalRequest->status }}</span>
                        @elseif(str_contains($finalRequest->status, 'Rejected by'))
                            <span class="text-red-500 font-semibold">{{ $finalRequest->status }}</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400 font-semibold">Pending</span>
                        @endif
                    </div>

                    <!-- Manager Status Section -->
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Manager Status:</span>
                        <div class="flex space-x-2 mt-1">
                            <!-- Approved -->
                            <div class="relative group">
                                <span class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                            </div>

                            <!-- Rejected -->
                            <div class="relative group">
                                <span class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                            </div>

                            <!-- Pending -->
                            <div class="relative group">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">{{ count($pendingManagers) }} Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to List Button -->
            <div class="mt-3">
                <a href="{{ route('staff.finallist') }}" 
                   class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Back to List
                </a>
            </div>
        </div>

       @extends('layouts.staff')

@section('content')
<!-- Main Container with Scrollable Content -->
<div class="h-screen flex flex-col overflow-hidden">
    <!-- Scrollable Content Area -->
    <div class="flex-1 overflow-y-auto p-2 pb-6">

        <!-- Final Request Details Section -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-4">
            <div class="flex justify-between items-start mb-2">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-300">Final Request Details</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Created: <span>{{ $finalRequest->created_at->format('M j, Y, g:i A') }}</span>
                </p>
            </div>

            <div class="mb-3">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-300">{{ $finalRequest->part_number }}</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Part Number</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                <div class="space-y-1">
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Unique Code:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->unique_code }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Part Name:</span>
                        <span class="text-gray-800 dark:text-gray-300">{{ $finalRequest->part_name }}</span>
                    </div>
                </div>

                <div class="space-y-1">
                    <div>
                        <span class="font-semibold text-gray-800 dark:text-gray-300">Status:</span>
                        @if(str_contains($finalRequest->status, 'Approved by'))
                            <span class="text-green-500 font-semibold">{{ $finalRequest->status }}</span>
                        @elseif(str_contains($finalRequest->status, 'Rejected by'))
                            <span class="text-red-500 font-semibold">{{ $finalRequest->status }}</span>
                        @else
                            <span class="text-gray-500 dark:text-gray-400 font-semibold">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Back to List Button -->
            <div class="mt-3">
                <a href="{{ route('staff.finallist') }}" 
                   class="px-3 py-1.5 bg-blue-500 text-white rounded hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Attachment Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg ring-2 ring-blue-500 ring-offset-2 mt-4 border border-gray-100 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                Attachment (Standard Time Form)
            </h2>

            @if ($finalRequest->final_approval_attachment)
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Final Approval
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        {{ $finalRequest->final_approval_attachment }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                @php
                                    $ext = pathinfo($finalRequest->final_approval_attachment, PATHINFO_EXTENSION);
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
                                        $path = storage_path("app/public/final_approval_attachments/{$finalRequest->final_approval_attachment}");
                                        $size = file_exists($path) ? round(filesize($path) / 1024, 2) . ' KB' : 'N/A';
                                    @endphp
                                    {{ $size }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('download.final_attachment', ['filename' => rawurlencode($finalRequest->final_approval_attachment)]) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Download
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-0.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400">No final approval attachment available.</p>
            @endif
        </div>
    </div>
</div>
@endsection



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