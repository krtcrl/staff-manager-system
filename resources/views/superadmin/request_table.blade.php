@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Request Management</h1>

        <!-- Request Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="overflow-x-auto"> <!-- Added overflow-x-auto to make table scrollable horizontally -->
                <table class="min-w-full divide-y divide-gray-200 table-auto"> <!-- table-auto ensures proper column width handling -->
                    <thead class="bg-indigo-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Unique Code</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Part Number</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Part Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Process Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Attachment</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Total Process</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($requests as $request)
                            <tr class="hover:bg-indigo-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $request->unique_code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->part_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->part_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->process_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($request->attachment)
                                    <a href="{{ asset('storage/attachments/' . $request->attachment) }}" class="text-blue-500 hover:text-blue-700" download>
                                        Download Attachment
                                    </a>
                                    @else
                                        No Attachment
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->total_processes }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-4">
                                    <button 
    onclick="openEditModal({{ $request->id }}, '{{ $request->unique_code }}', '{{ $request->part_number }}', '{{ $request->part_name }}')"
    class="text-indigo-600 hover:text-indigo-900 transition-colors flex items-center"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
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
                                                class="text-red-600 hover:text-red-900 transition-colors flex items-center"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Editing Request -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 transform transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Edit Request</h2>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form action="" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-6">
                            <label for="unique_code" class="block text-sm font-medium text-gray-700 mb-2">Unique Code</label>
                            <input 
                                type="text" 
                                id="editUniqueCode" 
                                name="unique_code" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                required
                            >
                        </div>

                        <div class="mb-6">
                            <label for="part_number" class="block text-sm font-medium text-gray-700 mb-2">Part Number</label>
                            <input 
                                type="text" 
                                id="editPartNumber" 
                                name="part_number" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                required
                            >
                        </div>
                        <div class="mb-6">
    <label for="part_name" class="block text-sm font-medium text-gray-700 mb-2">Part Name</label>
    <input 
        type="text" 
        id="editPartName" 
        name="part_name" 
        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
        required
    >
</div>


                        <div class="flex justify-end space-x-3">
                            <button 
                                type="button" 
                                onclick="closeEditModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors"
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
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editForm').action = '/superadmin/request/' + id;
    document.getElementById('editUniqueCode').value = uniqueCode;
    document.getElementById('editPartNumber').value = partNumber;
    document.getElementById('editPartName').value = partName; // Add this line to set the part_name value
}


        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this request?')) {
                document.getElementById('deleteForm-' + id).submit();
            }
        }
    </script>
@endsection
