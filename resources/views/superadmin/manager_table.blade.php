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

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Manager Management</h1>

        <!-- Manager Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Manager #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($managers as $manager)
                        <tr class="hover:bg-indigo-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $manager->manager_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $manager->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $manager->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-4">
                                    <button 
                                        onclick="openEditModal({{ $manager->id }}, '{{ $manager->manager_number }}', '{{ $manager->name }}', '{{ $manager->email }}')"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors flex items-center"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('superadmin.manager.destroy', $manager->id) }}" method="POST" class="inline" id="deleteForm-{{ $manager->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="button"
                                            onclick="confirmDelete({{ $manager->id }})"
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

        <!-- Modal for Editing Manager -->
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden transition-opacity duration-300">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 transform transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Edit Manager</h2>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form action="" method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="manager_number" class="block text-sm font-medium text-gray-700 mb-2">Manager #</label>
                            <input 
                                type="text" 
                                id="editManagerNumber" 
                                name="manager_number" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input 
                                type="text" 
                                id="editName" 
                                name="name" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input 
                                type="email" 
                                id="editEmail" 
                                name="email" 
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
        // Open Modal Function
        function openEditModal(id, managerNumber, name, email) {
            document.getElementById('editForm').action = '/superadmin/manager/' + id;
            document.getElementById('editManagerNumber').value = managerNumber;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Close Modal Function
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('editModal')) {
                closeEditModal();
            }
        });

        // Handle form submission
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
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded fixed top-4 right-4 z-50';
                    successMessage.innerHTML = `
                        <p class="font-bold">Success</p>
                        <p>${data.message}</p>
                    `;
                    document.body.appendChild(successMessage);
                    
                    // Remove message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                    
                    // Close modal and reload
                    closeEditModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    alert(data.message || 'Error updating manager');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating manager');
            });
        });

        // Confirm and handle delete
        function confirmDelete(managerId) {
            if (confirm('Are you sure you want to delete this manager?')) {
                const form = document.getElementById(`deleteForm-${managerId}`);
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Network response was not ok');
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded fixed top-4 right-4 z-50';
                        successMessage.innerHTML = `
                            <p class="font-bold">Success</p>
                            <p>${data.message}</p>
                        `;
                        document.body.appendChild(successMessage);
                        
                        // Remove message after 3 seconds and reload
                        setTimeout(() => {
                            successMessage.remove();
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'Error deleting manager');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Still reload since we know it deletes on refresh
                    window.location.reload();
                });
            }
        }
    </script>
@endsection