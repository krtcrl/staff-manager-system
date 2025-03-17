@extends('layouts.staff')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Header for Final Request Details -->
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Final Request Details</h2>
        </div>

        <!-- Request Details -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            <div class="space-y-2">
                <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                <div><span class="font-semibold">Description:</span> {{ $finalRequest->description }}</div>
                <div><span class="font-semibold">Process Type:</span> {{ $finalRequest->process_type }}</div>
                <div><span class="font-semibold">Created:</span> {{ $finalRequest->created_at->format('M j, Y, g:i A') }}</div>
            </div>

            <!-- Back to List Button -->
            <div class="mt-4">
                <a href="{{ route('staff.finallist') }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Back to List
                </a>
            </div>
        </div>
    </div>
@endsection