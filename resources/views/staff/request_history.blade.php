@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-4">
    <!-- Header with improved layout and responsive design -->
    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-300">Request History Completed</h2>

        <!-- Search and Date Filter with better responsive behavior -->
        <div class="w-full md:w-auto flex flex-col md:flex-row items-stretch md:items-center gap-2">
            <!-- Search Bar with clear button -->
            <div class="relative flex-grow">
                <input type="text" id="search-bar" placeholder="Search by Part Number or Unique Code" 
                    class="w-full pl-8 pr-8 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                <svg class="absolute left-2 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <button id="clear-search" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Date Filter with improved layout -->
            <div class="flex flex-col sm:flex-row items-stretch gap-2">
                <div class="flex items-center gap-1">
                    <input type="date" id="start-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                    <span class="text-gray-500 dark:text-gray-400 whitespace-nowrap">to</span>
                    <input type="date" id="end-date" 
                        class="flex-grow px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300">
                </div>
                <div class="flex gap-2">
                    <button id="apply-date-filter" 
                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition flex-grow">Filter</button>
                    <button id="clear-date-filter" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-400 transition">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table with improved accessibility and loading state -->
    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Loading overlay -->
        <div id="loading-overlay" class="absolute inset-0 bg-gray-100 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-50 flex items-center justify-center z-10 hidden">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
        
        <!-- Table container with sticky headers -->
        <div class="overflow-x-auto max-h-[calc(100vh-200px)]">
            <table class="w-full border-collapse" aria-label="Request history archive">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-gray-800 dark:bg-gray-700 text-white">
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">#</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Unique Code</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Part Number</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Part Name</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Staff ID</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Completed At</th>
                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-600 font-medium text-left">Created At</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @if($histories->isEmpty())
                        <tr>
                            <td colspan="7" class="py-8 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-300">No history records found</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">When requests are completed, they'll appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($histories as $index => $history)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150"
                                data-part-number="{{ strtolower($history->part_number) }}"
                                data-unique-code="{{ strtolower($history->unique_code) }}"
                                data-created-at="{{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->timestamp : '' }}"
                                >
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $histories->firstItem() + $index }}</td>
                                <!--<td class="py-3 px-4">
                                    <a href="{{ route('staff.request.details', ['unique_code' => $history->unique_code, 'page' => request()->page]) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $history->unique_code }}
                                    </a>
                                </td>-->
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $history->unique_code }}</td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $history->part_number }}</td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $history->part_name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-700 dark:text-gray-300">{{ $history->staff_id }}</td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400 text-sm">
                                    @if($history->completed_at)
                                        {{ \Carbon\Carbon::parse($history->completed_at)->timezone('Asia/Singapore')->format('M j, Y, g:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-500 dark:text-gray-400 text-sm">
                                    {{ \Carbon\Carbon::parse($history->created_at)->timezone('Asia/Singapore')->format('M j, Y, g:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination with improved styling -->
    @unless($histories->isEmpty())
    <div class="mt-4">
        {{ $histories->onEachSide(1)->links('vendor.pagination.tailwind') }}
    </div>
    @endunless
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchBar = document.getElementById('search-bar');
        const clearSearchBtn = document.getElementById('clear-search');
        
        searchBar.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm) {
                clearSearchBtn.classList.remove('hidden');
            } else {
                clearSearchBtn.classList.add('hidden');
            }
            
            filterTable();
        });
        
        clearSearchBtn.addEventListener('click', function() {
            searchBar.value = '';
            clearSearchBtn.classList.add('hidden');
            filterTable();
        });
        
        // Date filter functionality
        const startDateInput = document.getElementById('start-date');
        const endDateInput = document.getElementById('end-date');
        const applyDateFilterBtn = document.getElementById('apply-date-filter');
        const clearDateFilterBtn = document.getElementById('clear-date-filter');
        
        applyDateFilterBtn.addEventListener('click', filterTable);
        clearDateFilterBtn.addEventListener('click', function() {
            startDateInput.value = '';
            endDateInput.value = '';
            filterTable();
        });
        
        // Combined filter function
        function filterTable() {
            const searchTerm = searchBar.value.toLowerCase().trim();
            const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
            const endDate = endDateInput.value ? new Date(endDateInput.value) : null;
            
            document.querySelectorAll('#requests-table-body tr').forEach(row => {
                const partNumber = row.dataset.partNumber || '';
                const uniqueCode = row.dataset.uniqueCode || '';
                const createdAt = parseInt(row.dataset.createdAt) || 0;
                
                // Search filter
                const matchesSearch = !searchTerm || 
                    partNumber.includes(searchTerm) || 
                    uniqueCode.includes(searchTerm);
                
                // Date filter
                const matchesDate = (!startDate || new Date(createdAt) >= startDate) && 
                                  (!endDate || new Date(createdAt) <= endDate);
                
                // Show/hide based on filters
                row.style.display = matchesSearch && matchesDate ? '' : 'none';
            });
            
            updateRowNumbers();
        }
        
        // Update row numbers based on visible rows
        function updateRowNumbers() {
            const visibleRows = Array.from(document.querySelectorAll('#requests-table-body tr:not([style*="display: none"])'));
            const startNumber = parseInt("{{ $histories->firstItem() }}");
            
            visibleRows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = startNumber + index;
            });
        }
        
        // Initialize row numbers
        updateRowNumbers();
    });
</script>
@endsection