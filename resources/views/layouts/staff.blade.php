<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body x-data="{ sidebarOpen: true, modalOpen: false, userInput: '' }" class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
            <div class="p-4 flex justify-between items-center">
                <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">Staff Menu</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Button to Open Modal -->
            <div class="px-4">
                <button @click="modalOpen = true" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition">
                    Request
                </button>
            </div>

            <!-- Sidebar Links -->
            <ul class="mt-4">
                <li class="mb-2">
                    <a href="{{ route('staff.dashboard') }}" class="block p-2 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li class="mb-2">
                    <a href="#" class="block p-2 hover:bg-gray-700 rounded">Settings</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">

                    <!-- Placeholder for any other navbar content -->
                    <div></div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                            <span>{{ Auth::guard('staff')->user()->name }}</span>
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </nav>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>

        </div>
    </div>

    <!-- Pass part data from Laravel to JavaScript -->
    <script>
        window.partsData = @json($parts ?? []); // Use empty array as fallback
    </script>

    <!-- Modal -->
    <div x-show="modalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-96" 
             x-data="modalComponent"> <!-- Remove modalOpen parameter -->

            <!-- Auto-Generated Code -->
            <h2 class="text-lg font-semibold mb-4">Auto-Generated Code</h2>
            <div class="mb-2 p-3 bg-gray-100 border rounded text-center font-semibold text-blue-600">
                <span x-text="uniqueCode"></span>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm">
                <!-- Select Part Number -->
                <label for="partNumber" class="block text-sm font-medium text-gray-700">Part Number</label>
                <select id="partNumber" name="partNumber" x-model="selectedPart" 
                        @change="partName = parts.find(p => p.part_number === selectedPart)?.part_name || ''"
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1">
                    <option value="" disabled selected>Select a Part Number</option>
                    @foreach ($parts ?? [] as $part)
                        <option value="{{ $part->part_number }}">{{ $part->part_number }}</option>
                    @endforeach
                </select>
                <!-- Revision Type Dropdown -->
<label for="revisionType" class="block text-sm font-medium text-gray-700 mt-4">Revision Type</label>
<select id="revisionType" name="revisionType" x-model="revisionType" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1">
    <option value="" disabled selected>Select Revision Type</option>
    <option value="A">A</option>
    <option value="B">B</option>
    <option value="C">C</option>
    <option value="D">D</option>
    <!-- Add more options as needed -->
</select>

                <!-- Show the rest only after a part is selected -->
                <div x-show="selectedPart" x-transition>

                    <!-- Auto-filled Part Name -->
                    <label for="partName" class="block text-sm font-medium text-gray-700 mt-4">Part Name</label>
                    <input type="text" id="partName" name="partName" x-model="partName" class="w-full px-3 py-2 border rounded bg-gray-100" readonly>

                    <!-- Process Type Dropdown -->
                    <label for="processType" class="block text-sm font-medium text-gray-700 mt-4">Process Type</label>
                    <select id="processType" name="processType" x-model="processType" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1">
                        <option value="" disabled selected>Select Process Type</option>
                        <option value="Label Audit">Label Audit</option>
                        <option value="Production">Production</option>
                    </select>

                    <!-- Input for UPH -->
                    <label for="uph" class="block text-sm font-medium text-gray-700 mt-4">UPH (Units Per Hour)</label>
                    <input type="number" id="uph" name="uph" x-model="uph" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter UPH">

                    <!-- Description Input -->
                    <label for="description" class="block text-sm font-medium text-gray-700 mt-4">Description</label>
                    <textarea id="description" name="description" x-model="description" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter description"></textarea>

                   
                </div>
<!-- Attachment Input -->
<label for="attachment" class="block text-sm font-medium text-gray-700 mt-4">Attachment (PDF)</label>
<input 
    type="file" 
    id="attachment" 
    name="attachment" 
    accept=".pdf" 
    class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
>
                <!-- Buttons -->
                <!-- Buttons -->
<div class="mt-4 flex justify-end">
    <!-- Cancel Button -->
    <button 
        @click="modalOpen = false" 
        type="button" 
        class="mr-2 px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
    >
        Cancel
    </button>
    <!-- Submit Button -->
    <button 
        type="submit" 
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
    >
        Submit
    </button>
</div>
            </form>
        </div>
    </div>

    <script>
        function generateCode() {
            return 'PN-' + Math.floor(100000 + Math.random() * 900000);
        }

        document.addEventListener('alpine:init', () => {
    Alpine.data('modalComponent', () => ({
        uniqueCode: generateCode(),
        selectedPart: '',
        partName: '',
        processType: '',
        revisionType: '', // Add revisionType
        uph: '',
        description: '',
        parts: window.partsData || [],

        init() {
            this.uniqueCode = generateCode();
        },

        submitForm() {
            console.log("Submit button clicked!");
            if (!this.selectedPart || !this.processType || !this.uph || !this.revisionType) { // Add revisionType validation
                alert("Please fill in all required fields.");
                return;
            }

            // Create FormData object
            const formData = new FormData();
            formData.append('unique_code', this.uniqueCode);
            formData.append('part_number', this.selectedPart);
            formData.append('part_name', this.partName);
            formData.append('process_type', this.processType);
            formData.append('revision_type', this.revisionType); // Add revisionType
            formData.append('uph', this.uph);
            formData.append('description', this.description);
            formData.append('status', 'Pending');

            // Append the attachment file
            const attachmentInput = document.getElementById('attachment');
            if (attachmentInput.files.length > 0) {
                formData.append('attachment', attachmentInput.files[0]);
            }

            // Log FormData to verify
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            console.log("Sending data:", formData);

            fetch("{{ route('requests.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: formData // Use FormData instead of JSON
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    this.$dispatch('close-modal'); // Emit event to close modal
                    this.selectedPart = '';
                    this.partName = '';
                    this.processType = '';
                    this.revisionType = ''; // Reset revisionType
                    this.uph = '';
                    this.description = '';
                    attachmentInput.value = ''; // Clear the file input
                } else {
                    alert("Error submitting request.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to submit. Please try again.");
            });
        }

    }));
});
    </script>
</body>
</html>