@extends('layouts.finalmanager')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Header for Final Request List -->
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Final Request List</h2>
        </div>

        <!-- Scrollable Table Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex justify-center">
            <table class="min-w-full divide-y divide-gray-200 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">No.</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Unique Code</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Part Number</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Description</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Process Type</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Created</th>
                    </tr>
                </thead>
                <tbody id="final-requests-table-body">
                    @foreach($finalRequests as $index => $finalRequest)
                        <tr class="hover:bg-gray-300 transition-colors">
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequests->firstItem() + $index }}</td>
                            <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                                <a href="{{ route('finalmanager.request.details', ['unique_code' => $finalRequest->unique_code]) }}">
                                    {{ $finalRequest->unique_code }}
                                </a>
                            </td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->part_number }}</td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->description }}</td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->process_type }}</td>
                            <td class="py-2 px-3 text-sm text-gray-700">
                                {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $finalRequests->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection