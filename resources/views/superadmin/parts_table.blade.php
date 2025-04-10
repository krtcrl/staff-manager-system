{{-- resources/views/superadmin/parts_table.blade.php --}}
@extends('layouts.superadmin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Parts Management</h1>

        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Part Number</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Part Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($parts as $part)
                    <tr class="hover:bg-indigo-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $part->part_number }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $part->part_name }}</td>

                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center space-x-4">
                                <button 
                                    onclick="openEditModal({{ $part->id }}, '{{ $part->part_number }}', '{{ $part->description }}', '{{ $part->category }}')"
                                    class="text-indigo-600 hover:text-indigo-900 transition-colors flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('superadmin.parts.destroy', $part->id) }}" method="POST" class="inline" id="deleteForm-{{ $part->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="button"
                                        onclick="confirmDelete({{ $part->id }})"
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

        <!-- Modal -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Part</h2>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Part Number</label>
                        <input type="text" id="editPartNumber" name="part_number" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <input type="text" id="editDescription" name="description" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Category</label>
                        <input type="text" id="editCategory" name="category" class="w-full px-4 py-2 border rounded">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, partNumber, description, category) {
            document.getElementById('editForm').action = '/superadmin/parts/' + id;
            document.getElementById('editPartNumber').value = partNumber;
            document.getElementById('editDescription').value = description;
            document.getElementById('editCategory').value = category;
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'PUT',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeEditModal();
                    setTimeout(() => window.location.reload(), 500);
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('Error updating part');
            });
        });

        function confirmDelete(partId) {
            if (confirm('Are you sure you want to delete this part?')) {
                const form = document.getElementById(`deleteForm-${partId}`);
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    window.location.reload();
                });
            }
        }
    </script>
@endsection
