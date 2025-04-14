@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Request Management</h1>
            
            @if(session('success'))
            <div class="mt-4 md:mt-0">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Request Table Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Part Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attachment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Process</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($requests as $request)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $request->unique_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->part_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->part_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="capitalize">{{ $request->process_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($request->attachment)
                                    <a href="{{ asset('storage/attachments/' . $request->attachment) }}" 
                                       class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                       download
                                       aria-label="Download attachment">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                    @else
                                        <span class="text-gray-400">No Attachment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $request->total_processes }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $request->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            onclick="openEditModal({{ $request->id }}, '{{ $request->unique_code }}', '{{ $request->part_number }}', '{{ $request->part_name }}')"
                                            class="text-indigo-600 hover:text-indigo-900 inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            aria-label="Edit request"
                                        >
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>

                                        <span class="text-gray-300">|</span>
                                        
                                        <form action="{{ route('superadmin.request.destroy', $request->id) }}" method="POST" class="inline" id="deleteForm-{{ $request->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="button"
                                                onclick="confirmDelete({{ $request->id }})"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                aria-label="Delete request"
                                            >
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No requests found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($requests->hasPages())
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $requests->links() }}
            </div>
            @endif
        </div>

        <!-- Edit Request Modal -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300 ease-in-out">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 transform transition-all duration-300 ease-in-out" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800" id="modal-headline">Edit Request</h2>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500 transition-colors focus:outline-none" aria-label="Close modal">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form action="" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label for="editUniqueCode" class="block text-sm font-medium text-gray-700 mb-1">Unique Code</label>
                                <input 
                                    type="text" 
                                    id="editUniqueCode" 
                                    name="unique_code" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                    aria-required="true"
                                >
                            </div>

                            <div>
                                <label for="editPartNumber" class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                                <input 
                                    type="text" 
                                    id="editPartNumber" 
                                    name="part_number" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                    aria-required="true"
                                >
                            </div>
                            
                            <div>
                                <label for="editPartName" class="block text-sm font-medium text-gray-700 mb-1">Part Name</label>
                                <input 
                                    type="text" 
                                    id="editPartName" 
                                    name="part_name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                    aria-required="true"
                                >
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button 
                                type="button" 
                                onclick="closeEditModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
        function openEditModal(id, uniqueCode, partNumber, partName) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            form.action = '/superadmin/request/' + id;
            document.getElementById('editUniqueCode').value = uniqueCode;
            document.getElementById('editPartNumber').value = partNumber;
            document.getElementById('editPartName').value = partName;
            
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Focus on first input when modal opens
            setTimeout(() => {
                document.getElementById('editUniqueCode').focus();
            }, 100);
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this request? This action cannot be undone.')) {
                document.getElementById('deleteForm-' + id).submit();
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
        });
    </script>
@endsection