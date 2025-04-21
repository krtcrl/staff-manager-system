@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-2">
        <!-- Compact Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Manager Management</h1>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-red-500 font-medium">Security Alert:</span> 
                    Manager accounts have elevated system privileges. Changes may impact critical operations.
                </p>
            </div>
            
            <div class="flex items-center space-x-2">

            <!-- Added Search Bar - Matching staff_table.php -->
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
                        placeholder="Search by name or email..."
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
            <button 
        onclick="openAddModal()"
        class="mt-2 md:mt-0 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
    >
        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Add Manager
    </button>
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

        

        <!-- Manager Table Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <div class="max-h-[calc(100vh-220px)] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager #</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="managerTableBody">
                            @forelse($managers as $index => $manager)
                                <tr class="hover:bg-gray-50 manager-row" 
                                    data-name="{{ strtolower($manager->name) }}" 
                                    data-email="{{ strtolower($manager->email) }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                        {{ ($managers->currentPage() - 1) * $managers->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $manager->manager_number }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $manager->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $manager->email }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium">
                                        <div class="flex items-center space-x-1">
                                            <button 
                                                onclick="openEditModal({{ $manager->id }}, '{{ $manager->manager_number }}', '{{ $manager->name }}', '{{ $manager->email }}')"
                                                class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                                aria-label="Edit manager"
                                            >
                                                <svg class="h-4 w-4 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </button>

                                            <span class="text-gray-300 text-xs">|</span>
                                            
                                            <form action="{{ route('superadmin.manager.destroy', $manager->id) }}" method="POST" class="inline" id="deleteForm-{{ $manager->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="button"
                                                    onclick="confirmDelete({{ $manager->id }})"
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                    aria-label="Delete manager"
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
                                    <td colspan="5" class="px-4 py-3 text-center text-xs text-gray-500" id="noResults">
                                        No managers found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($managers->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sticky bottom-0 pagination-container">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0">
                    <div class="text-xs text-gray-500">
                        Showing <span id="showingFrom">{{ $managers->firstItem() }}</span> to <span id="showingTo">{{ $managers->lastItem() }}</span> of <span id="totalResults">{{ $managers->total() }}</span> results
                    </div>
                    <div class="space-x-1">
                        {{-- Previous --}}
                        @if($managers->onFirstPage())
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $managers->previousPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Previous</a>
                        @endif

                        {{-- Page Numbers (current ±1) --}}
                        @php
                            $start = max($managers->currentPage() - 1, 1);
                            $end = min($managers->currentPage() + 1, $managers->lastPage());
                        @endphp

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $managers->currentPage())
                                <span class="px-2 py-1 rounded border border-indigo-300 text-xs text-white bg-indigo-600">{{ $page }}</span>
                            @else
                                <a href="{{ $managers->url($page) }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">{{ $page }}</a>
                            @endif
                        @endfor

                        {{-- Next --}}
                        @if($managers->hasMorePages())
                            <a href="{{ $managers->nextPageUrl() }}" class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Next</a>
                        @else
                            <span class="px-2 py-1 rounded border border-gray-300 text-xs text-gray-400 bg-gray-100 cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- Add Manager Modal -->
<div id="addModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow w-full max-w-md mx-4">
        <div class="p-4">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-bold text-gray-800">Add New Manager</h2>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Security Note:</strong> Managers have elevated privileges. Verify all details before creating an account.
                        </p>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('superadmin.manager.store') }}" method="POST" id="addForm">
                @csrf
                
                <div class="space-y-3">
                    <div>
                        <label for="addManagerNumber" class="block text-xs font-medium text-gray-700 mb-1">Manager #</label>
                        <input 
                            type="text" 
                            id="addManagerNumber" 
                            name="manager_number" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        >
                        <div id="add-manager-number-error" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="addName" class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                        <input 
                            type="text" 
                            id="addName" 
                            name="name" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        >
                        <div id="add-name-error" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="addEmail" class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                        <input 
                            type="email" 
                            id="addEmail" 
                            name="email" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                        >
                        <div id="add-email-error" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>

                    <div>
                        <label for="addPassword" class="block text-xs font-medium text-gray-700 mb-1">Temporary Password</label>
                        <input 
                            type="password" 
                            id="addPassword" 
                            name="password" 
                            class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                            required
                            minlength="8"
                        >
                        <div id="add-password-error" class="text-xs text-red-500 mt-1 hidden"></div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button 
                        type="button" 
                        onclick="closeAddModal()" 
                        class="px-3 py-1 border border-gray-300 rounded shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        id="addSubmitButton"
                        class="px-3 py-1 border border-transparent rounded shadow-sm text-xs font-medium text-white bg-red-600 hover:bg-red-700 flex items-center justify-center min-w-[80px]"
                    >
                        <span id="addSubmitText">Create Manager</span>
                        <svg id="addSubmitSpinner" class="hidden h-4 w-4 animate-spin ml-1 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

        <!-- Edit Manager Modal -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow w-full max-w-md mx-4">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg font-bold text-gray-800">Edit Manager</h2>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Security Warning:</strong> Manager accounts have administrative privileges.
                                    Changes may affect system security and operations.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-3">
                            <div>
                                <label for="editManagerNumber" class="block text-xs font-medium text-gray-700 mb-1">Manager #</label>
                                <input 
                                    type="text" 
                                    id="editManagerNumber" 
                                    name="manager_number" 
                                    class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                >
                            </div>

                            <div>
                                <label for="editName" class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                                <input 
                                    type="text" 
                                    id="editName" 
                                    name="name" 
                                    class="w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    required
                                >
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
        // All your existing modal functions remain exactly the same
        function openEditModal(id, managerNumber, name, email) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            form.action = '{{ route("superadmin.manager.update", ":id") }}'.replace(':id', id);
            document.getElementById('editManagerNumber').value = managerNumber;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            setTimeout(() => {
                document.getElementById('editManagerNumber').focus();
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmDelete(id) {
            if (confirm('SECURITY ALERT: Are you sure you want to delete this manager account?\n\nThis will permanently revoke all administrative privileges and cannot be undone.')) {
                document.getElementById('deleteForm-' + id).action = '{{ route("superadmin.manager.destroy", "") }}/' + id;
                document.getElementById('deleteForm-' + id).submit();
            }
        }

        // New functions for add modal
function openAddModal() {
    const modal = document.getElementById('addModal');
    const form = document.getElementById('addForm');
    
    // Reset form
    form.reset();
    document.getElementById('add-manager-number-error').classList.add('hidden');
    document.getElementById('add-name-error').classList.add('hidden');
    document.getElementById('add-email-error').classList.add('hidden');
    document.getElementById('add-password-error').classList.add('hidden');
    
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    setTimeout(() => {
        document.getElementById('addManagerNumber').focus();
    }, 100);
}

function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Add modal event listener
document.getElementById('addModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});

// Handle form submission for add manager
document.getElementById('addForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitButton = document.getElementById('addSubmitButton');
    const submitText = document.getElementById('addSubmitText');
    const spinner = document.getElementById('addSubmitSpinner');
    
    // Show loading state
    submitButton.disabled = true;
    submitText.textContent = 'Processing...';
    spinner.classList.remove('hidden');
    
    // Clear previous errors
    document.getElementById('add-manager-number-error').classList.add('hidden');
    document.getElementById('add-name-error').classList.add('hidden');
    document.getElementById('add-email-error').classList.add('hidden');
    document.getElementById('add-password-error').classList.add('hidden');
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw data;
        }

        if (data.success) {
            // Close modal and refresh table
            closeAddModal();
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'bg-green-50 border-l-4 border-green-500 p-2 rounded shadow-sm mb-4';
            successDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-green-800">
                            Manager account created successfully!
                        </p>
                    </div>
                </div>
            `;
            
            // Insert success message
            const header = document.querySelector('.container.mx-auto > .flex.flex-col');
            header.insertAdjacentElement('afterend', successDiv);
            
            // Remove after 5 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 5000);
            
            // Refresh the manager table
            await searchManagers('');
            
            // Reset form
            this.reset();
        }
    } catch (error) {
        console.error('Error:', error);
        
        // Handle validation errors
        if (error.errors) {
            if (error.errors.manager_number) {
                document.getElementById('add-manager-number-error').textContent = error.errors.manager_number[0];
                document.getElementById('add-manager-number-error').classList.remove('hidden');
            }
            if (error.errors.name) {
                document.getElementById('add-name-error').textContent = error.errors.name[0];
                document.getElementById('add-name-error').classList.remove('hidden');
            }
            if (error.errors.email) {
                document.getElementById('add-email-error').textContent = error.errors.email[0];
                document.getElementById('add-email-error').classList.remove('hidden');
            }
            if (error.errors.password) {
                document.getElementById('add-password-error').textContent = error.errors.password[0];
                document.getElementById('add-password-error').classList.remove('hidden');
            }
        } else {
            alert('An error occurred. Please try again.');
        }
    } finally {
        // Reset button state
        submitButton.disabled = false;
        submitText.textContent = 'Create Manager';
        spinner.classList.add('hidden');
    }
});

        // NEW: AJAX search function for managers
        async function searchManagers(searchTerm) {
            try {
                const response = await fetch(`{{ route('superadmin.manager.table') }}?search=${encodeURIComponent(searchTerm)}`);
                const html = await response.text();
                
                // Create temporary DOM element to parse the response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update table body
                document.getElementById('managerTableBody').innerHTML = 
                    tempDiv.querySelector('#managerTableBody').innerHTML;
                
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
            searchManagers('');
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
                        searchManagers(this.value.trim());
                    } else {
                        searchManagers('');
                    }
                }, 500); // 500ms debounce
            });
            
            // Handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    searchManagers(this.value.trim());
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