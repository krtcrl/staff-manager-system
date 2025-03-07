@extends('layouts.staff')

@section('content')
    <h1 class="text-2xl font-semibold">Request Details</h1>
    <p class="mt-4">Here are the details for the request with Unique Code: <span class="font-semibold">{{ $request->unique_code }}</span>.</p>

    <div class="mt-8 bg-white p-6 rounded-lg shadow-sm">
        <div class="space-y-4">
            <div>
                <span class="font-semibold">Unique Code:</span> {{ $request->unique_code }}
            </div>
            <div>
                <span class="font-semibold">Description:</span> {{ $request->description }}
            </div>
            <div>
                <span class="font-semibold">Status:</span>
                @if(str_contains($request->status, 'Approved by'))
                    <span class="text-green-500 font-semibold">{{ $request->status }}</span>
                @elseif(str_contains($request->status, 'Rejected by'))
                    <span class="text-red-500 font-semibold">{{ $request->status }}</span>
                @else
                    <span class="text-gray-500 font-semibold">Pending</span>
                @endif
            </div>
            <div>
                <span class="font-semibold">Part Number:</span> {{ $request->part_number }}
            </div>
            <div>
                <span class="font-semibold">Part Name:</span> {{ $request->part_name }}
            </div>
            <div>
                <span class="font-semibold">Process Type:</span> {{ $request->process_type }}
            </div>
            <div>
                <span class="font-semibold">UPH (Units Per Hour):</span> {{ $request->uph }}
            </div>
            <div>
                <span class="font-semibold">Manager Status:</span>
                <div class="flex space-x-4 mt-2">
                    <!-- Approved -->
                    <div class="relative group">
                        <span class="text-green-500 font-semibold">{{ count($approvedManagers) }} Approved</span>
                        <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                            @if(count($approvedManagers) > 0)
                                <ul>
                                    @foreach($approvedManagers as $manager)
                                        <li>{{ $manager }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No managers have approved this request.</p>
                            @endif
                        </div>
                    </div>
                    <!-- Rejected -->
                    <div class="relative group">
                        <span class="text-red-500 font-semibold">{{ count($rejectedManagers) }} Rejected</span>
                        <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                            @if(count($rejectedManagers) > 0)
                                <ul>
                                    @foreach($rejectedManagers as $manager)
                                        <li>{{ $manager }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No managers have rejected this request.</p>
                            @endif
                        </div>
                    </div>
                    <!-- Pending -->
                    <div class="relative group">
                        <span class="text-gray-500 font-semibold">{{ count($pendingManagers) }} Pending</span>
                        <div class="absolute hidden group-hover:block bg-white border border-gray-300 p-2 rounded-lg shadow-sm">
                            @if(count($pendingManagers) > 0)
                                <ul>
                                    @foreach($pendingManagers as $manager)
                                        <li>{{ $manager }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No managers have pending actions on this request.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Back Button -->
    <div class="p-4 bg-gray-100 border-t fixed bottom-0 w-full">
        <a href="{{ route('staff.dashboard') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Back to Dashboard
        </a>
    </div>
@endsection