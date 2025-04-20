@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-2">
        <!-- Compact Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Request History</h1>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-red-500 font-medium">Note:</span> 
                    Historical records are permanent. Deletion affects audit trails and reporting.
                </p>
            </div>
            
            <!-- Added Search Bar - Matching previous implementations -->
            <div class="mt-2 md:mt-0">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="liveSearch" 
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded-md text-xs" 
                        placeholder="Search by part number or name..."
                        value="{{ request('search') }}"
                    >
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 {{ request('search') ? '' : 'hidden' }}" id="clearSearchBtn">
                        <button 
                            type="button" 
                            onclick="clearSearch()"
                            class="text-gray-400 hover:text-gray-500"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            @if(session('success'))
            <div class="mt-1 md:mt-0">
                <div class="bg-green-50 border-l-4 border-green-500 p-2 rounded shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-2">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Request History Table Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div class="max-h-[calc(100vh-220px)] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Code</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="requestHistoryTableBody">
                            @forelse($requestHistories as $index => $history)
                                <tr class="hover:bg-gray-50 request-history-row" 
                                    data-part-number="{{ strtolower($history->part_number) }}" 
                                    data-part-name="{{ strtolower($history->part_name) }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ ($requestHistories->currentPage() - 1) * $requestHistories->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $history->staff_id }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $history->unique_code }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $history->part_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $history->part_name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($history->created_at)->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">
                                        <form action="{{ route('superadmin.requesthistory.destroy', $history->id) }}" method="POST" class="inline" id="deleteForm-{{ $history->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button"
                                                onclick="confirmDelete({{ $history->id }})"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                aria-label="Delete history"
                                            >
                                                <svg class="h-4 w-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-center text-xs text-gray-500" id="noResults">
                                        No request histories found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($requestHistories->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sticky bottom-0 pagination-container">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                    <div class="text-xs text-gray-500">
                        Showing <span id="showingFrom">{{ $requestHistories->firstItem() }}</span> to <span id="showingTo">{{ $requestHistories->lastItem() }}</span> of <span id="totalResults">{{ $requestHistories->total() }}</span> results
                    </div>
                    <div class="space-x-1">
                        @if($requestHistories->onFirstPage())
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $requestHistories->previousPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Previous</a>
                        @endif
                        
                        @php
                            $start = max($requestHistories->currentPage() - 1, 1);
                            $end = min($requestHistories->currentPage() + 1, $requestHistories->lastPage());
                        @endphp

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $requestHistories->currentPage())
                                <span class="px-2 py-1 rounded border border-indigo-300 text-xs text-white bg-indigo-600">{{ $page }}</span>
                            @else
                                <a href="{{ $requestHistories->url($page) }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endfor
                        
                        @if($requestHistories->hasMorePages())
                            <a href="{{ $requestHistories->nextPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Next</a>
                        @else
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this historical record? This action is permanent and affects audit trails.')) {
                document.getElementById('deleteForm-' + id).action = '{{ route("superadmin.requesthistory.destroy", "") }}/' + id;
                document.getElementById('deleteForm-' + id).submit();
            }
        }

        // NEW: AJAX search function for request histories
        async function searchRequestHistories(searchTerm) {
            try {
                const response = await fetch(`{{ route('superadmin.requesthistory.table') }}?search=${encodeURIComponent(searchTerm)}`);
                const html = await response.text();
                
                // Create temporary DOM element to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update table body
                document.getElementById('requestHistoryTableBody').innerHTML = 
                    tempDiv.querySelector('#requestHistoryTableBody').innerHTML;
                
                // Update pagination info
                document.getElementById('showingFrom').textContent = 
                    tempDiv.querySelector('#showingFrom').textContent;
                document.getElementById('showingTo').textContent = 
                    tempDiv.querySelector('#showingTo').textContent;
                document.getElementById('totalResults').textContent = 
                    tempDiv.querySelector('#totalResults').textContent;
                
                // Update pagination controls
                const paginationContainer = document.querySelector('.pagination-container');
                if (paginationContainer) {
                    const newPagination = tempDiv.querySelector('.pagination-container');
                    paginationContainer.innerHTML = newPagination ? newPagination.innerHTML : '';
                }
                
                // Update no results message
                const noResults = document.getElementById('noResults');
                const newNoResults = tempDiv.getElementById('noResults');
                if (newNoResults) {
                    noResults.className = newNoResults.className;
                    noResults.style.display = newNoResults.style.display;
                }
                
            } catch (error) {
                console.error('Search failed:', error);
            }
        }

        // MODIFIED clearSearch function
        function clearSearch() {
            const searchInput = document.getElementById('liveSearch');
            searchInput.value = '';
            searchRequestHistories('');
            document.getElementById('clearSearchBtn').classList.add('hidden');
        }

        // Initialize live search with debounce
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('liveSearch');
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                // Show/hide clear button immediately
                document.getElementById('clearSearchBtn').classList.toggle('hidden', !this.value);
                
                searchTimeout = setTimeout(() => {
                    if (this.value.trim()) {
                        searchRequestHistories(this.value.trim());
                    } else {
                        searchRequestHistories('');
                    }
                }, 500); // 500ms debounce
            });
            
            // Handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    searchRequestHistories(this.value.trim());
                }
            });
            
            // Initialize clear button if there's existing search
            if (searchInput.value) {
                document.getElementById('clearSearchBtn').classList.remove('hidden');
            }
        });
    </script>
@endsection