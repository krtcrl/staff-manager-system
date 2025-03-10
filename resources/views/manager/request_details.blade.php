@extends('layouts.manager')

@section('content')
    <div class="h-screen flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Column: Page Title and Request Details -->
                <div class="w-full lg:w-1/2 flex flex-col"> <!-- Added flex to ensure proper spacing -->
                    <h1 class="text-2xl font-semibold mb-4">Request Details</h1>

                    <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col flex-grow"> <!-- Ensures button stays at bottom -->
                        <div class="flex-grow"> <!-- Pushes the button down -->
                            <p class="mb-2">Here are the details for the request with Unique Code: 
                                <span class="font-semibold">{{ $request->unique_code }}</span>.
                            </p>

                            <div class="mb-4 flex space-x-2">
                                <form action="{{ route('manager.request.approve', $request->unique_code) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                        Approve Request
                                    </button>
                                </form>
                                <form action="{{ route('manager.request.reject', $request->unique_code) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                        Reject Request
                                    </button>
                                </form>
                            </div>

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
                                <div><span class="font-semibold">Description:</span> {{ $request->description }}</div>
                                <div><span class="font-semibold">Revision:</span> {{ $request->revision_type }}</div>

                                <div>
                                    <span class="font-semibold">Status:</span>
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
                                        <span class="text-gray-500 font-semibold">Pending</span>
                                    @endif
                                </div>
                                <div><span class="font-semibold">Part Number:</span> {{ $request->part_number }}</div>
                                <div><span class="font-semibold">Part Name:</span> {{ $request->part_name }}</div>
                                <div><span class="font-semibold">Process Type:</span> {{ $request->process_type }}</div>
                                <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}</div>
                            </div>
                        </div>

                        <!-- Back to List Button at the Bottom -->
                        <div class="mt-4"> <!-- Adds spacing from content above -->
                            <a href="{{ route('manager.request-list') }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Attachment -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-white p-4 rounded-lg shadow-sm h-[calc(100vh-10rem)]">
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Attachment</h2>

                        @if ($request->attachment)
                            <div class="h-[calc(100%-3rem)] overflow-y-auto border border-gray-200 rounded-lg p-2">
                                <iframe 
                                    src="{{ asset('storage/' . $request->attachment) }}" 
                                    class="w-full h-full border rounded-lg">
                                    Your browser does not support PDFs. 
                                    <a href="{{ asset('storage/' . $request->attachment) }}" class="text-blue-500 hover:underline">
                                        Download the PDF
                                    </a>
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
