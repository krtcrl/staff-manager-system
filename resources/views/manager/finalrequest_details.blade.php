@extends('layouts.manager')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Final Request Details</h1>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="space-y-4">
                <div><span class="font-semibold">Unique Code:</span> {{ $finalRequest->unique_code }}</div>
                <div><span class="font-semibold">Part Number:</span> {{ $finalRequest->part_number }}</div>
                <div><span class="font-semibold">Part Name:</span> {{ $finalRequest->part_name }}</div>
                <div><span class="font-semibold">Description:</span> {{ $finalRequest->description }}</div>
                <div><span class="font-semibold">Revision Type:</span> {{ $finalRequest->revision_type }}</div>
                <div>
                    <span class="font-semibold">Status:</span>
                    @if(str_contains($finalRequest->status, 'Approved by'))
                        <span class="text-green-500 font-semibold">{{ $finalRequest->status }}</span>
                    @elseif(str_contains($finalRequest->status, 'Rejected by'))
                        <span class="text-red-500 font-semibold">{{ $finalRequest->status }}</span>
                    @else
                        <span class="text-gray-500 font-semibold">Pending</span>
                    @endif
                </div>
                <div><span class="font-semibold">UPH (Units Per Hour):</span> {{ $finalRequest->uph }}</div>
                <div>
                    <span class="font-semibold">Attachment:</span>
                    @if($finalRequest->attachment)
                        <a href="{{ asset('storage/' . $finalRequest->attachment) }}" target="_blank" class="text-blue-500 hover:underline">View Attachment</a>
                    @else
                        <span class="text-gray-500">No attachment</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection