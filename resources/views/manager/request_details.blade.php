@extends('layouts.manager')

@section('content')
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Page Title -->
        <h1 class="text-2xl font-semibold p-4 bg-gray-100 border-b">Request Details</h1>

        <!-- Scrollable Content Container -->
        <div class="flex-1 overflow-y-auto p-6">
            <p class="mb-4">Here are the details for the request with Unique Code: 
                <span class="font-semibold">{{ $request->unique_code }}</span>.
            </p>

            <!-- Approval/Rejection Buttons -->
            <div class="mb-6 flex space-x-4">
            <form action="{{ route('manager.request.approve', $request->unique_code) }}" method="POST">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
        Approve Request
    </button>
</form>
                <form action="{{ route('manager.request.reject', $request->unique_code) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Reject Request
                    </button>
                </form>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Request Details -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="space-y-4">
                    <div><span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}</div>
                    <div><span class="font-semibold">Description:</span> {{ $request->description }}</div>
                    <div><span class="font-semibold">Status:</span> {{ $request->status }}</div>
                    <div><span class="font-semibold">Part Number:</span> {{ $request->part_number }}</div>
                    <div><span class="font-semibold">Part Name:</span> {{ $request->part_name }}</div>
                    <div><span class="font-semibold">Process Type:</span> {{ $request->process_type }}</div>
                    <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}</div>
                </div>
            </div>
        </div>

        <!-- Sticky Back Button -->
        <div class="p-4 bg-gray-100 border-t fixed bottom-0 w-full">
            <a href="{{ route('manager.dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Back to Dashboard
            </a>
        </div>
    </div>
@endsection