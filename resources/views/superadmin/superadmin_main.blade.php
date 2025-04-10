@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column - Stats Cards -->
        <div class="w-full lg:w-1/2">
            <!-- First Row of Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Total Staff Card -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Staff</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $staffCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Managers Card -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Managers</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $managersCount }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row of Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Total Parts Card -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Parts</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $partsCount }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Part Processes Card -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Part Processes</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $partProcessesCount }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h4v12H4V6zm6 0h4v12h-4V6zm6 0h4v12h-4V6z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Pie Chart with Time Filter -->
        <div class="w-full lg:w-1/2">
            <div class="bg-white p-6 rounded-lg shadow-md h-full">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Approval Workflow</h2>
                   
                </div>
                <div class="h-96 relative">
                    <canvas id="approvalFlowChart"></canvas>
                    <div id="chartLegend" class="mt-4 flex flex-wrap justify-center"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart with actual data
        const chartCtx = document.getElementById('approvalFlowChart').getContext('2d');
        
        const approvalChart = new Chart(chartCtx, {
            type: 'pie',
            data: {
                labels: ['Pending Pre-Approval', 'Final Approved', 'Completed'],
                datasets: [{
                    data: [
                        {{ $requestsCount }},
                        {{ $finalRequestsCount }}, 
                        {{ $requestHistoriesCount }}
                    ],
                    backgroundColor: [
                        'rgba(156, 163, 175, 0.7)',  // gray for pending
                        'rgba(59, 130, 246, 0.7)',    // blue for approved
                        'rgba(16, 185, 129, 0.7)'     // green for completed
                    ],
                    borderColor: [
                        'rgba(156, 163, 175, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Create legend with actual data
        const legendContainer = document.getElementById('chartLegend');
        legendContainer.innerHTML = '';
        
        ['Pending Pre-Approval', 'Final Approved', 'Completed'].forEach((label, i) => {
            const legendItem = document.createElement('div');
            legendItem.className = 'flex items-center mx-4 my-1';
            
            const colorBox = document.createElement('div');
            colorBox.className = 'w-4 h-4 rounded-full mr-2';
            colorBox.style.backgroundColor = approvalChart.data.datasets[0].backgroundColor[i];
            
            const text = document.createElement('span');
            text.className = 'text-sm text-gray-600';
            text.textContent = `${label} (${approvalChart.data.datasets[0].data[i]})`;
            
            legendItem.appendChild(colorBox);
            legendItem.appendChild(text);
            legendContainer.appendChild(legendItem);
        });

        // Time filter would be implemented with real API calls
        document.getElementById('timeFilter').addEventListener('change', function() {
            // This would be replaced with actual API call to fetch filtered data
            // For now it just shows all time data
        });
    });
</script>
@endsection