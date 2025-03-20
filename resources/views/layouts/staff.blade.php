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
                        <!-- Icon: Document Review -->
                        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 16h8M8 12h8m-8-4h4M4 4h16v16H4z"/>
                        </svg>
                        <span :class="sidebarOpen ? 'block' : 'hidden'">Pre Approval</span>
                    </a>
                </li>

                <!-- Final Request List Link -->
                <li class="mb-2">
                    <a href="{{ route('staff.finallist') }}" class="flex items-center p-2 hover:bg-gray-700 rounded">
                        <!-- Icon: Checkmark or Stamp -->
                        <svg :class="sidebarOpen ? 'w-5 h-5 mr-2' : 'w-8 h-8 mx-auto'" 
                            xmlns="http://www.w3.org/2000/svg" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7"/> <!-- ✅ Checkmark -->
                        </svg>
                        <span :class="sidebarOpen ? 'block' : 'hidden'">Final Approval</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar -->
            <nav class="bg-white shadow-md border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
                    <!-- Left Section (Logo or Menu) -->
                    <div class="flex items-center space-x-4">
                        <!-- Removed "Staff Dashboard" -->
                        <a href="{{ route('staff.dashboard') }}" class="text-blue-600 font-bold text-lg hover:text-blue-700 transition">
                            <!-- You can place a logo or icon here if needed -->
                        </a>
                    </div>

                    <!-- Right Section (User Profile & Dropdown) -->
                    <div class="relative" x-data="{ open: false }">
                        <!-- User Info Button -->
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 px-4 py-2 bg-gray-200 hover:bg-gray-400 rounded-lg transition">
                            <!-- User Name -->
                            <span class="text-black font-medium">{{ Auth::guard('staff')->user()->name }}</span>
                            <!-- Dropdown Icon -->
                            <svg class="w-5 h-5 transition-transform duration-300" 
                                 :class="open ? 'rotate-180' : ''" 
                                 fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" 
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" 
                                      clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-400 z-50 transition-all duration-300"
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100" 
                             x-transition:leave-end="opacity-0 scale-95">
                            <!-- Profile -->
                            <div class="px-4 py-3 border-b">
                                <p class="text-sm font-medium text-gray-700">Signed in as</p>
                                <p class="text-sm text-gray-500">{{ Auth::guard('staff')->user()->email }}</p>
                            </div>

                            <!-- Dropdown Links -->
                            <div class="py-2">
                                <a href="#" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    Settings
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="border-t">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition">
                                        Logout
                                    </button>
                                </form>
                            </div>
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
    <div class="bg-white p-6 rounded-lg shadow-lg transition-all duration-300 ease-in-out flex flex-col w-[500px]" 
         x-data="modalComponent">
        
        <form @submit.prevent="submitForm">
            <!-- Step 1: Auto-Generated Code, Part Number, Revision Type, Description -->
            <div x-show="step === 1">
                <h2 class="text-lg font-semibold mb-4">Step 1: Basic Information</h2>
                <!-- Auto-Generated Code -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Auto-Generated Code</label>
                    <div class="p-3 bg-gray-100 border rounded text-center font-semibold text-blue-600">
                        <span x-text="uniqueCode"></span>
                    </div>
                </div>

                <!-- Part Number Combobox -->
                <div class="mb-4">
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
                </div>

                <!-- Revision Type Dropdown -->
                <div class="mb-4">
                    <label for="revisionType" class="block text-sm font-medium text-gray-700">Revision Type</label>
                    <select id="revisionType" name="revisionType" x-model="revisionType" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1">
                        <option value="" disabled selected>Select Revision Type</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <!-- Description Input -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea id="description" name="description" x-model="description" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter description"></textarea>
                </div>

                <!-- Next Button -->
                <div class="flex justify-end">
                    <button type="button" @click="nextStep" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 2: Pre Approval (Part Name, UPH, Attachment) -->
            <div x-show="step === 2">
                <h2 class="text-lg font-semibold mb-4">Step 2: Pre Approval</h2>
                <!-- Auto-filled Part Name -->
                <div class="mb-4">
                    <label for="partName" class="block text-sm font-medium text-gray-700">Part Name</label>
                    <input type="text" id="partName" name="partName" x-model="partName" class="w-full px-3 py-2 border rounded bg-gray-100 mt-1" readonly>
                </div>

                <!-- Input for UPH -->
                <div class="mb-4">
                    <label for="uph" class="block text-sm font-medium text-gray-700">UPH (Units Per Hour)</label>
                    <input type="number" id="uph" name="uph" x-model="uph" class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" placeholder="Enter UPH">
                </div>

                <!-- Attachment Input -->
                <div class="mb-4">
                    <label for="attachment" class="block text-sm font-medium text-gray-700">Pre Approval Attachment (PDF)</label>
                    <input 
                        type="file" 
                        id="attachment" 
                        name="attachment" 
                        accept=".pdf" 
                        class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
                    >
                </div>


                <!-- Previous and Next Buttons -->
                <div class="flex justify-between">
                    <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                        Previous
                    </button>
                    <button type="button" @click="nextStep" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Next
                    </button>
                </div>
            </div>

            <!-- Step 3: Final Approval (Standard Yield, Actual Yield, Bottle Neck UPH, and Attachment) -->
<div x-show="step === 3">
    <h2 class="text-lg font-semibold mb-4">Step 3: Final Approval</h2>

    <!-- Standard Yield Section -->
    <div class="mb-4">
        <h3 class="text-sm font-medium text-gray-700 mb-2">Standard Yield</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="standardYieldPercentage" class="block text-sm font-medium text-gray-700">(%)</label>
                <input type="number" id="standardYieldPercentage" name="standardYieldPercentage" x-model="standardYieldPercentage" 
                    class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                    placeholder="Enter %" step="0.01">
            </div>
            <div>
                <label for="standardYieldDollarPerHour" class="block text-sm font-medium text-gray-700">($/hr)</label>
                <input type="number" id="standardYieldDollarPerHour" name="standardYieldDollarPerHour" x-model="standardYieldDollarPerHour" 
                    class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                    placeholder="Enter $/hr" step="0.01">
            </div>
        </div>
    </div>

    <!-- Actual Yield Section -->
    <div class="mb-4">
        <h3 class="text-sm font-medium text-gray-700 mb-2">Actual Yield</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="actualYieldPercentage" class="block text-sm font-medium text-gray-700">(%)</label>
                <input type="number" id="actualYieldPercentage" name="actualYieldPercentage" x-model="actualYieldPercentage" 
                    class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                    placeholder="Enter %" step="0.01">
            </div>
            <div>
                <label for="actualYieldDollarPerHour" class="block text-sm font-medium text-gray-700">($/hr)</label>
                <input type="number" id="actualYieldDollarPerHour" name="actualYieldDollarPerHour" x-model="actualYieldDollarPerHour" 
                    class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1" 
                    placeholder="Enter $/hr" step="0.01">
            </div>
        </div>
    </div>

    <!-- Bottle Neck UPH -->
    <div class="mb-4">
        <label for="bottleNeckUph" class="block text-sm font-medium text-gray-700">Bottle Neck UPH</label>
        <input type="number" id="bottleNeckUph" name="bottleNeckUph" x-model="bottleNeckUph"
            class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
            placeholder="Enter Bottle Neck UPH">
    </div>

    <!-- Final Approval Attachment -->
    <div class="mb-4">
        <label for="finalApprovalAttachment" class="block text-sm font-medium text-gray-700">Final Approval Attachment (PDF)</label>
        <input 
            type="file" 
            id="finalApprovalAttachment" 
            name="finalApprovalAttachment" 
            accept=".pdf"
            class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-300 mt-1"
        >
    </div>

    <!-- Previous and Submit Buttons -->
    <div class="flex justify-between">
        <button type="button" @click="prevStep" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Previous
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
            Submit
        </button>
    </div>
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
            step: 1, // Current step
            uniqueCode: '', 
            selectedPart: '', 
            partNumberSearch: '', 
            partName: '', 
            revisionType: '',
            uph: '',
            standardYieldPercentage: '', 
            standardYieldDollarPerHour: '', 
            actualYieldPercentage: '', 
            actualYieldDollarPerHour: '',
            bottleNeckUph: '',                      // ➕ Added Bottle Neck UPH
            description: '',
            parts: window.partsData || [], 
            filteredParts: window.partsData || [], 

            init() {
                this.uniqueCode = generateCode();
            },

            // Filter parts based on search term (first 3 results)
            filterParts() {
                if (this.partNumberSearch) {
                    this.filteredParts = this.parts
                        .filter(part => 
                            part.part_number.toLowerCase().includes(this.partNumberSearch.toLowerCase()))
                        .slice(0, 3); 
                } else {
                    this.filteredParts = this.parts; 
                }
            },

            // Set selectedPart when a valid part is chosen
            setSelectedPart(value) {
                const selectedPartObj = this.parts.find(part => part.part_number === value);
                if (selectedPartObj) {
                    this.selectedPart = value; 
                    this.partName = selectedPartObj.part_name; 
                } else {
                    this.selectedPart = ''; 
                    this.partName = ''; 
                }
            },

            // Navigation between steps
            nextStep() {
                if (this.step === 1 && (!this.selectedPart || !this.revisionType)) {
                    alert("Please fill in all required fields.");
                    return;
                }
                if (this.step === 2 && (!this.uph)) {
                    alert("Please fill in all required fields.");
                    return;
                }
                this.step++;
            },

            prevStep() {
                this.step--;
            },

            // Form submission logic
            submitForm() {
                console.log("Submit button clicked!");

                if (!this.selectedPart || !this.uph || !this.revisionType || 
                    !this.standardYieldPercentage || !this.standardYieldDollarPerHour || 
                    !this.actualYieldPercentage || !this.actualYieldDollarPerHour || 
                    !this.bottleNeckUph) {
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
                formData.append('standard_yield_percentage', this.standardYieldPercentage);
                formData.append('standard_yield_dollar_per_hour', this.standardYieldDollarPerHour);
                formData.append('actual_yield_percentage', this.actualYieldPercentage);
                formData.append('actual_yield_dollar_per_hour', this.actualYieldDollarPerHour);
                formData.append('bottle_neck_uph', this.bottleNeckUph);  // ➕ Added Bottle Neck UPH
                formData.append('description', this.description || '');
                formData.append('status', 'Pending');

                // Attachments
                const attachmentInput = document.getElementById('attachment');
                if (attachmentInput.files.length > 0) {
                    formData.append('attachment', attachmentInput.files[0]);
                }

                // Final Approval Attachment
                const finalApprovalAttachmentInput = document.getElementById('finalApprovalAttachment');
                if (finalApprovalAttachmentInput.files.length > 0) {
                    formData.append('final_approval_attachment', finalApprovalAttachmentInput.files[0]);
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
                    body: formData 
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
                        this.$dispatch('close-modal'); 
                        this.resetForm(); 
                    } else {
                        alert("Error submitting request.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Failed to submit. Please try again.");
                });
            },

            // Reset form fields
            resetForm() {
                this.step = 1;
                this.uniqueCode = generateCode();
                this.selectedPart = '';
                this.partNumberSearch = '';
                this.partName = '';
                this.revisionType = '';
                this.uph = '';
                this.standardYieldPercentage = '';
                this.standardYieldDollarPerHour = '';
                this.actualYieldPercentage = '';
                this.actualYieldDollarPerHour = '';
                this.bottleNeckUph = '';          // Reset Bottle Neck UPH
                this.description = '';

                // Clear file inputs
                document.getElementById('attachment').value = '';
                document.getElementById('finalApprovalAttachment').value = '';
            }
        }));
    });
</script>

</body>
</html>