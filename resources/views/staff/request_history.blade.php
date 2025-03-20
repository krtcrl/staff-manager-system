@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-semibold mb-4">Request History</h2>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Unique Code</th>
                <th class="border border-gray-300 px-4 py-2">Part Number</th>
                <th class="border border-gray-300 px-4 py-2">Description</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Completed At</th>
                <th class="border border-gray-300 px-4 py-2">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $history)
                <tr class="text-center">
                    <td class="border border-gray-300 px-4 py-2">{{ $history->unique_code }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $history->part_number }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $history->description }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($history->status) }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $history->completed_at ? \Carbon\Carbon::parse($history->completed_at)->format('Y-m-d H:i:s') : 'N/A' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->format('Y-m-d H:i:s') : 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">No history records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $histories->links() }}
    </div>
</div>
@endsection
