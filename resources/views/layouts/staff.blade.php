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
<body x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'true', modalOpen: false, userInput: '' }" 
      x-init="localStorage.setItem('sidebarOpen', sidebarOpen)" 
      class="font-sans antialiased bg-gray-100 text-gray-900 transition-all duration-300 overflow-hidden">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-800 text-white transition-all duration-300 min-h-screen">
            <div class="p-4 flex justify-between items-center">
                <h2 :class="sidebarOpen ? 'block' : 'hidden'" class="text-lg font-semibold">Staff Menu</h2>
                <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

<!-- Button to Open Modal -->
<div class="px-4">
    <button 
        @click="modalOpen = true; $nextTick(() => { $data.modalComponent.resetForm(); })" 
        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition flex items-center justify-center"
    >
        <!-- Plus Icon -->
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-6 h-6'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <!-- Text "Request" (visible only when sidebar is open) -->
        <span :class="sidebarOpen ? 'block' : 'hidden'">Request</span>
    </button>
</div>

            <!-- Sidebar Links -->
<ul class="mt-4">
    <!-- Dashboard Link -->
    <li class="mb-2">
    <a href="{{ route('staff.dashboard') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
        <!-- Clipboard Checkmark Icon -->
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m2-5H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
        </svg>
        <span :class="sidebarOpen ? 'block' : 'hidden'">Pre Approval</span>
    </a>
</li>


    <!-- Final Request List Link -->
    <li class="mb-2">
    <a href="{{ route('staff.finallist') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
        <!-- Check Circle Icon -->
        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 4a8 8 0 11-8 8 8 8 0 018-8z"></path>
        </svg>
        <span :class="sidebarOpen ? 'block' : 'hidden'">Final Approval</span>
    </a>
</li>


    <!-- Settings Link -->
    <li class="mb-2">
        <a href="#" class="flex items-center p-2 hover:bg-gray-700 rounded">
            <!-- Settings Icon -->
            <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span :class="sidebarOpen ? 'block' : 'hidden'">Settings</span>
        </a>
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
<div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak>
    <div class="bg-white p-6 rounded-lg shadow-lg transition-all duration-300 ease-in-out flex flex-col" 
         :class="selectedPart && revisionType ? 'w-[700px]' : 'w-96'" 
         x-data="modalComponent">
        
        <form @submit.prevent="submitForm">
            <div class="flex flex-1">
                <!-- Left Section (Part Number and Revision Type) -->
                <div class="flex-1 pr-6">
                    <!-- Auto-Generated Code -->
                    <h2 class="text-lg font-semibold mb-4">Auto-Generated Code</h2>
                    <div class="mb-2 p-3 bg-gray-100 border rounded text-center font-semibold text-blue-600">
                        <span x-text="uniqueCode"></span>
                    </div>

                    <!-- Part Number Combobox -->
                    <label for="partNumber" class="block text-sm font-medium text-gray-700">Part Number</label>
                    <input 
                        type="text" 
                        id="partNumber" 
                        list="partNumberList" 
                        x-model="partNumberSearch" 
                        @input="filterParts()" 
                        @change="setSelectedPart($event.target.value)" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                        placeholder="Type or select a part number"
                        autocomplete="off"
                    >
                    <datalist id="partNumberList">
                        <template x-for="part in filteredParts" :key="part.part_number">
                            <option :value="part.part_number" x-text="part.part_number"></option>
                        </template>
                    </datalist>

                    <!-- Revision Type Dropdown -->
                    <label for="revisionType" class="block text-sm font-medium text-gray-700 mt-4">Revision Type</label>
                    <select id="revisionType" name="revisionType" x-model="revisionType" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1">
                        <option value="" disabled selected>Select Revision Type</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>

                    <!-- Attachment Input -->
                    <label for="attachment" class="block text-sm font-medium text-gray-700 mt-4">Attachment (PDF)</label>
                    <input 
                        type="file" 
                        id="attachment" 
                        name="attachment" 
                        accept=".pdf" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
                    >
                </div>

                <!-- Right Section (Additional Form Fields) -->
                <div x-show="selectedPart && revisionType" 
                     x-transition:enter="transition ease-in-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-x-20" 
                     x-transition:enter-end="opacity-100 translate-x-0" 
                     x-transition:leave="transition ease-in-out duration-300" 
                     x-transition:leave-start="opacity-100 translate-x-0" 
                     x-transition:leave-end="opacity-0 translate-x-20" 
                     class="flex-1 pl-6 border-l border-gray-200">
                    <!-- Auto-filled Part Name -->
                    <label for="partName" class="block text-sm font-medium text-gray-700">Part Name</label>
                    <input type="text" id="partName" name="partName" x-model="partName" class="w-full px-3 py-2 border rounded bg-gray-100 mt-1" readonly>


                    <!-- Input for UPH -->
                    <label for="uph" class="block text-sm font-medium text-gray-700 mt-4">UPH (Units Per Hour)</label>
                    <input type="number" id="uph" name="uph" x-model="uph" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter UPH">

                    <!-- Description Input -->
                    <label for="description" class="block text-sm font-medium text-gray-700 mt-4">Description (Optional)</label>
                    <textarea id="description" name="description" x-model="description" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter description"></textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end space-x-2">
                <!-- Cancel Button -->
                <button 
                    @click="modalOpen = false" 
                    type="button" 
                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500"
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
        uniqueCode: '', // Initialize as empty
        selectedPart: '', // Selected part number (bound to input)
        partNumberSearch: '', // Search term for part number
        partName: '', // Auto-filled part name
        revisionType: '',
        uph: '',
        description: '',
        parts: window.partsData || [], // All parts
        filteredParts: window.partsData || [], // Filtered parts for dropdown

        init() {
            // Generate a unique code when the modal is initialized
            this.uniqueCode = generateCode();
        },

        // Function to filter parts based on search term and show only the first 3 results
        filterParts() {
            if (this.partNumberSearch) {
                // Filter parts and limit to the first 3 results
                this.filteredParts = this.parts
                    .filter(part => 
                        part.part_number.toLowerCase().includes(this.partNumberSearch.toLowerCase()))
                    .slice(0, 3); // Show only the first 3 results
            } else {
                this.filteredParts = this.parts; // Show all parts if search term is empty
            }
        },

        // Function to set selectedPart when a valid part number is selected
        setSelectedPart(value) {
            const selectedPartObj = this.parts.find(part => part.part_number === value);
            if (selectedPartObj) {
                this.selectedPart = value; // Set selectedPart to the valid part number
                this.partName = selectedPartObj.part_name; // Auto-fill part name
            } else {
                this.selectedPart = ''; // Reset selectedPart if the input is invalid
                this.partName = ''; // Clear part name
            }
        },

        submitForm() {
    console.log("Submit button clicked!");
    if (!this.selectedPart || !this.uph || !this.revisionType) {
        alert("Please fill in all required fields.");
        return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append('unique_code', this.uniqueCode);
    formData.append('part_number', this.selectedPart);
    formData.append('part_name', this.partName);
    formData.append('revision_type', this.revisionType);
    formData.append('uph', this.uph);
    formData.append('description', this.description || ''); // Make description optional
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
            this.resetForm(); // Reset the form fields
        } else {
            alert("Error submitting request.");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Failed to submit. Please try again.");
    });
},

        // Function to reset the form fields
        resetForm() {
            this.uniqueCode = generateCode(); // Regenerate the unique code
            this.selectedPart = '';
            this.partNumberSearch = '';
            this.partName = '';
            this.revisionType = '';
            this.uph = '';
            this.description = '';
            const attachmentInput = document.getElementById('attachment');
            attachmentInput.value = ''; // Clear the file input
        }
    }));
});
    </script>
</body>
</html>