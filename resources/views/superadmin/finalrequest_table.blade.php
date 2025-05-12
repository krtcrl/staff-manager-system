@extends('layouts.superadmin')

@section('content')
@if(session('success'))
<div class="mt-1 md:mt-0" id="successMessage">
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

    <div class="container mx-auto px-4 py-2">
        <!-- Compact Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Final Request Management</h1>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-red-500 font-medium">Critical:</span> 
                    Final requests are production-approved documents. Changes require authorization.
                </p>
            </div>
            
            <!-- Added Search Bar -->
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
                        placeholder="Search part number, name or code..."
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
            <!-- End Search Bar -->
            
        </div>

        <!-- Final Request Table Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div class="max-h-[calc(100vh-220px)] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Code</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Approval Attachment</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="requestsTableBody">
                            @forelse($finalRequests as $index => $request)
                                <tr class="hover:bg-gray-50 request-row" 
                                    data-part-number="{{ strtolower($request->part_number) }}" 
                                    data-part-name="{{ strtolower($request->part_name) }}"
                                    data-unique-code="{{ strtolower($request->unique_code) }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ ($finalRequests->currentPage() - 1) * $finalRequests->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $request->unique_code }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $request->part_number }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $request->part_name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        @if($request->final_approval_attachment)
                                        <a href="{{ asset('storage/' . $request->final_approval_attachment) }}" 
                                           class="inline-flex items-center text-indigo-600 hover:text-indigo-900"
                                           download
                                           aria-label="View attachment">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            View Attachment
                                        </a>
                                        @else
                                            <span class="text-gray-400">No Attachment</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                        {{ $request->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">
                                        <div class="flex items-center space-x-1">
                                            <button 
                                                onclick="openEditModal({{ $request->id }}, '{{ $request->unique_code }}', '{{ $request->part_number }}', '{{ $request->part_name }}')"
                                                class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                                aria-label="Edit request"
                                            >
                                                <svg class="h-4 w-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>

                                            <span class="text-gray-300 text-xs">|</span>
                                            
                                            <form action="{{ route('superadmin.finalrequest.destroy', $request->id) }}" method="POST" class="inline" id="deleteForm-{{ $request->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button"
                                                    onclick="confirmDelete({{ $request->id }})"
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                    aria-label="Delete request"
                                                >
                                                    <svg class="h-4 w-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-center text-xs text-gray-500" id="noResults">
                                        No final requests found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($finalRequests->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sticky bottom-0 pagination-container">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                    <div class="text-xs text-gray-500">
                        Showing <span id="showingFrom">{{ $finalRequests->firstItem() }}</span> to <span id="showingTo">{{ $finalRequests->lastItem() }}</span> of <span id="totalResults">{{ $finalRequests->total() }}</span> results
                    </div>
                    <div class="space-x-1">
                        {{-- Previous Page Link --}}
                        @if($finalRequests->onFirstPage())
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $finalRequests->previousPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Previous</a>
                        @endif

                        {{-- Only show 3 page links: current - 1, current, current + 1 --}}
                        @php
                            $start = max($finalRequests->currentPage() - 1, 1);
                            $end = min($finalRequests->currentPage() + 1, $finalRequests->lastPage());
                        @endphp

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $finalRequests->currentPage())
                                <span class="px-2 py-1 rounded border border-indigo-300 text-xs text-white bg-indigo-600">{{ $page }}</span>
                            @else
                                <a href="{{ $finalRequests->url($page) }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if($finalRequests->hasMorePages())
                            <a href="{{ $finalRequests->nextPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Next</a>
                        @else
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Edit Request Modal -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow w-full max-w-md mx-4">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-bold text-gray-800">Edit Final Request</h2>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Final requests are production-approved. Verify all changes with quality control.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-3">
                            <div>
                                <label for="editUniqueCode" class="block text-xs font-medium text-gray-700 mb-1">Unique Code</label>
                                <input 
                                    type="text" 
                                    id="editUniqueCode" 
                                    name="unique_code" 
                                    class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                >
                            </div>

                            <div>
                                <label for="editPartNumber" class="block text-xs font-medium text-gray-700 mb-1">Part Number</label>
                                <input 
                                    type="text" 
                                    id="editPartNumber" 
                                    name="part_number" 
                                    class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                >
                            </div>
                            
                            <div>
                                <label for="editPartName" class="block text-xs font-medium text-gray-700 mb-1">Part Name</label>
                                <input 
                                    type="text" 
                                    id="editPartName" 
                                    name="part_name" 
                                    class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                >
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-2">
                            <button 
                                type="button" 
                                onclick="closeEditModal()" 
                                class="px-3 py-1 border border-gray-300 rounded shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-3 py-1 border border-transparent rounded shadow-sm text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700"
                            >
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
          // Auto-remove success message after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = 'opacity 0.5s ease';
            successMessage.style.opacity = '0';
            
            // Remove the element after fade out
            setTimeout(() => {
                successMessage.remove();
            }, 500);
        }, 5000); // 5000ms = 5 seconds
    }
});	
        // All your existing modal functions remain exactly the same
        function openEditModal(id, uniqueCode, partNumber, partName) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            form.action = '{{ route("superadmin.finalrequest.update", "") }}/' + id;
            document.getElementById('editUniqueCode').value = uniqueCode;
            document.getElementById('editPartNumber').value = partNumber;
            document.getElementById('editPartName').value = partName;
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            setTimeout(() => {
                document.getElementById('editUniqueCode').focus();
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this final request? This will permanently remove approved production data.')) {
                document.getElementById('deleteForm-' + id).action = '{{ route("superadmin.finalrequest.destroy", "") }}/' + id;
                document.getElementById('deleteForm-' + id).submit();
            }
        }

        // NEW: AJAX search function
        async function searchRequests(searchTerm) {
            try {
                const response = await fetch(`{{ route('superadmin.finalrequest.table') }}?search=${encodeURIComponent(searchTerm)}`);
                const html = await response.text();
                
                // Create temporary DOM element to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update table body
                document.getElementById('requestsTableBody').innerHTML = 
                    tempDiv.querySelector('#requestsTableBody').innerHTML;
                
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
            searchRequests('');
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
                        searchRequests(this.value.trim());
                    } else {
                        searchRequests('');
                    }
                }, 500); // 500ms debounce
            });
            
            // Handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    searchRequests(this.value.trim());
                }
            });
            
            // Initialize clear button if there's existing search
            if (searchInput.value) {
                document.getElementById('clearSearchBtn').classList.remove('hidden');
            }
        });

        // Keep all your existing modal event listeners
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
        });
    </script>
@endsection