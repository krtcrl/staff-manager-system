@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-6">

    <!-- ✅ Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-300">Request History</h2>
        
        <!-- ✅ Search and Date Filter -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="search-bar" placeholder="Search by Part Number" 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="flex items-center space-x-2">
                <input type="date" id="start-date" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                <span class="text-gray-500 dark:text-gray-400">to</span>
                <input type="date" id="end-date" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-600" />
                <button id="apply-date-filter" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Apply</button>
                <button id="clear-date-filter" 
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Clear</button>
            </div>
        </div>
    </div>

    <!-- ✅ Scrollable Table Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden dark:bg-gray-900">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
            <thead class="bg-gray-800 dark:bg-gray-700">
                <tr>
                    <th class="py-2 px-3 text-sm font-semibold text-white">No.</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Unique Code</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Part Number</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Part Name</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Staff ID</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Completed At</th>
                    <th class="py-2 px-3 text-sm font-semibold text-white">Created At</th>
                </tr>
            </thead>
            
            <tbody id="requests-table-body">
                @forelse($histories as $index => $history)
                <tr class="hover:bg-gray-300 transition-colors dark:hover:bg-gray-700">
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ $histories->firstItem() + $index }}
                    </td>
                    <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                        {{ $history->unique_code }}
                    </td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $history->part_number }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $history->part_name ?? 'N/A' }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">{{ $history->staff_id }}</td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
                        @if($history->completed_at)
                            {{ \Carbon\Carbon::parse($history->completed_at)->timezone('Asia/Singapore')->format('M j, Y h:i A') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="py-2 px-3 text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($history->created_at)->timezone('Asia/Singapore')->format('M j, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-600 dark:text-gray-400">No history records found.</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">When new requests are completed, they'll appear here.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ✅ Pagination -->
    @unless($histories->isEmpty())
    <div class="mt-4 dark:text-gray-300">
        {{ $histories->links() }}
    </div>
    @endunless
</div>

<script>
    function closeAlert() {
        document.getElementById('success-alert').style.display = 'none';
    }

    document.getElementById('search-bar').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        let rows = document.querySelectorAll('#requests-table-body tr');

        rows.forEach(row => {
            let partNumber = row.querySelector('td:nth-child(3)').textContent.toLowerCase(); // Ensure correct column index
            if (partNumber.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateRowNumbers();
    });

    document.getElementById('apply-date-filter').addEventListener('click', function() {
        let startDate = new Date(document.getElementById('start-date').value);
        let endDate = new Date(document.getElementById('end-date').value);
        filterByDateRange(startDate, endDate);
    });

    document.getElementById('clear-date-filter').addEventListener('click', function() {
        document.getElementById('start-date').value = '';
        document.getElementById('end-date').value = '';
        let rows = document.querySelectorAll('#requests-table-body tr');
        rows.forEach(row => row.style.display = '');
        updateRowNumbers();
    });

    function filterByDateRange(startDate, endDate) {
        let rows = document.querySelectorAll('#requests-table-body tr');

        rows.forEach(row => {
            let dateCell = row.querySelector('td:nth-child(7)'); // Corrected index for 'Created' column
            let dateText = dateCell.textContent.trim(); // Get the text content of the 'Created' column
            let requestDate = new Date(dateText);

            if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        updateRowNumbers();
    }

    function updateRowNumbers() {
        let rows = document.querySelectorAll('#requests-table-body tr');
        rows.forEach((row, index) => {
            row.querySelector('td:nth-child(1)').textContent = index + 1;
        });
    }
</script>

@endsection
