@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-2">
        <!-- Compact Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h1 class="text-xl font-bold text-gray-900">Staff Management</h1>
            
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

        <!-- Staff Table Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div class="max-h-[calc(100vh-220px)] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staff as $index => $member)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ ($staff->currentPage() - 1) * $staff->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $member->email }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">
                                        <div class="flex items-center space-x-1">
                                            <button 
                                                onclick="openEditModal({{ $member->id }}, '{{ $member->name }}', '{{ $member->email }}')"
                                                class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                                aria-label="Edit staff"
                                            >
                                                <svg class="h-4 w-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>

                                            <span class="text-gray-300 text-xs">|</span>
                                            
                                            <form action="{{ route('superadmin.staff.destroy', $member->id) }}" method="POST" class="inline" id="deleteForm-{{ $member->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button"
                                                    onclick="confirmDelete({{ $member->id }})"
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                    aria-label="Delete staff"
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
                                    <td colspan="4" class="px-4 py-3 text-center text-xs text-gray-500">
                                        No staff members found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($staff->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sticky bottom-0">
        <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
            <div class="text-xs text-gray-500">
                Showing {{ $staff->firstItem() }} to {{ $staff->lastItem() }} of {{ $staff->total() }} results
            </div>
            <div class="space-x-1">
                {{-- Previous --}}
                @if($staff->onFirstPage())
                    <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $staff->previousPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Previous</a>
                @endif

                {{-- Page Numbers (current ±1) --}}
                @php
                    $start = max($staff->currentPage() - 1, 1);
                    $end = min($staff->currentPage() + 1, $staff->lastPage());
                @endphp

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $staff->currentPage())
                        <span class="px-2 py-1 rounded border border-indigo-300 text-xs text-white bg-indigo-600">{{ $page }}</span>
                    @else
                        <a href="{{ $staff->url($page) }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next --}}
                @if($staff->hasMorePages())
                    <a href="{{ $staff->nextPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Next</a>
                @else
                    <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    </div>
@endif

        </div>

        <!-- Edit Staff Modal -->
<div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow w-full max-w-md mx-4">
        <div class="p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold text-gray-800">Edit Staff Member</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <div>
                        <label for="editName" class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                        <input 
                            type="text" 
                            id="editName" 
                            name="name" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        >
                        <div id="name-error" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="editEmail" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input 
                            type="email" 
                            id="editEmail" 
                            name="email" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        >
                        <div id="email-error" class="text-xs text-red-500 mt-1 hidden"></div>
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
                        id="submitButton"
                        class="px-3 py-1 border border-transparent rounded shadow-sm text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 flex items-center justify-center min-w-[80px]"
                    >
                        <span id="submitText">Save Changes</span>
                        <svg id="submitSpinner" class="hidden h-4 w-4 animate-spin ml-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>


    <script>
        function openEditModal(id, name, email) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            form.action = '{{ route("superadmin.staff.update", ":id") }}'.replace(':id', id);
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            setTimeout(() => {
                document.getElementById('editName').focus();
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Handle form submission with success message
       
// For delete operations
function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this staff? This action cannot be undone.')) {
            // Submit the form with the proper route
            document.getElementById('deleteForm-' + id).action = '{{ route("superadmin.staff.destroy", "") }}/' + id;
            document.getElementById('deleteForm-' + id).submit();
        }
    }
        // Rest of your modal handling code...
    
    // Modal close handlers
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