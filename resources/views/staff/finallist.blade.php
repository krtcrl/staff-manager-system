@extends('layouts.staff')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Header for Final Request List -->
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Final Request List</h2>
            
            <!-- Search and Date Filter Container -->
            <div class="flex items-center space-x-4">
                <!-- Search Bar -->
                <div class="relative">
                    <input type="text" id="search-bar" placeholder="Search by Part Number" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Date Filter -->
                <div class="flex items-center space-x-2">
                    <input type="date" id="start-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-gray-500">to</span>
                    <input type="date" id="end-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button id="apply-date-filter" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Apply</button>
                    <button id="clear-date-filter" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">Clear</button>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-center">
                <thead>
                    <tr>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">No.</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Unique Code</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Part Number</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Description</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Process Type</th>
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Capacity Planning</th> <!-- Manager 1 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Planning Manager</th> <!-- Manager 2 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Product Manager</th> <!-- Manager 3 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">EE Manager</th> <!-- Manager 4 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">QAE Manager</th> <!-- Manager 5 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">General Manager</th> <!-- Manager 6 Status -->
                        <th class="py-2 px-3 text-sm font-semibold text-gray-700">Created</th>
                    </tr>
                </thead>
                <tbody id="final-requests-table-body">
                    @foreach($finalRequests as $index => $finalRequest)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequests->firstItem() + $index }}</td>
                            <td class="py-2 px-3 text-sm text-blue-500 hover:underline">
                                <a href="{{ route('staff.final.details', ['unique_code' => $finalRequest->unique_code]) }}">
                                    {{ $finalRequest->unique_code }}
                                </a>
                            </td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->part_number }}</td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->description }}</td>
                            <td class="py-2 px-3 text-sm text-gray-700">{{ $finalRequest->process_type }}</td>
                            <!-- Manager 1 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_1_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_1_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 2 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_2_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_2_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 3 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_3_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_3_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 4 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_4_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_4_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 5 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_5_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_5_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <!-- Manager 6 Status -->
                            <td class="py-2 px-3 text-sm text-center">
                                @if($finalRequest->manager_6_status === 'approved')
                                    <span class="text-green-500">✔️</span>
                                @elseif($finalRequest->manager_6_status === 'rejected')
                                    <span class="text-red-500">❌</span>
                                @else
                                    <span class="text-gray-500">⏳</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-sm text-gray-700">
                                {{ $finalRequest->created_at->format('M j, Y, g:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $finalRequests->links() }}
        </div>
    </div>

    <script>
        document.getElementById('search-bar').addEventListener('input', function() {
            let searchTerm = this.value.toLowerCase();
            let rows = document.querySelectorAll('#final-requests-table-body tr');

            rows.forEach(row => {
                let partNumber = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                if (partNumber.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function filterByDateRange(startDate, endDate) {
            let rows = document.querySelectorAll('#final-requests-table-body tr');
            rows.forEach(row => {
                let dateCell = row.querySelector('td:nth-child(12)').textContent.trim(); // 12th column = Created Date
                let requestDate = new Date(dateCell);
                if ((!startDate || requestDate >= startDate) && (!endDate || requestDate <= endDate)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        document.getElementById('apply-date-filter').addEventListener('click', function() {
            let startDateInput = document.getElementById('start-date').value;
            let endDateInput = document.getElementById('end-date').value;
            let startDate = startDateInput ? new Date(startDateInput) : null;
            let endDate = endDateInput ? new Date(endDateInput) : null;
            filterByDateRange(startDate, endDate);
        });

        document.getElementById('clear-date-filter').addEventListener('click', function() {
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';
            let rows = document.querySelectorAll('#final-requests-table-body tr');
            rows.forEach(row => row.style.display = '');
        });
    </script>
@endsection