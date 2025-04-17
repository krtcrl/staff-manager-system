@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left Column - Stats Cards -->
        <div class="w-full lg:w-1/2">
            <!-- First Row of Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Total Staff Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Staff</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $staffCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full text-purple-600 dark:text-purple-400">
                            <!-- Users Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 4a4 4 0 11-8 0 4 4 0 018 0zM12 14a4 4 0 00-4-4H8a4 4 0 00-4 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Managers Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Managers</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $managersCount }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600 dark:text-yellow-400">
                            <!-- Clipboard List Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h2.586A2 2 0 0011 4h2a2 2 0 001.414.586H17a2 2 0 012 2v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row of Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Total Parts Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Parts</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $partsCount }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600 dark:text-blue-400">
                            <!-- Cube Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m0-4v8m8-4v8m-16-4v8m8 4v-8m-8 0l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Part Processes Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Part Processes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $partProcessesCount }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-full text-orange-600 dark:text-orange-400">
                            <!-- Cog Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0a1.72 1.72 0 002.591.93c.823-.486 1.932.262 1.446 1.085a1.72 1.72 0 00.93 2.59c.92.3.92 1.603 0 1.902a1.72 1.72 0 00-.93 2.591c.486.823-.262 1.932-1.085 1.446a1.72 1.72 0 00-2.59.93c-.3.92-1.603.92-1.902 0a1.72 1.72 0 00-2.591-.93c-.823.486-1.932-.262-1.446-1.085a1.72 1.72 0 00-.93-2.59c-.92-.3-.92-1.603 0-1.902a1.72 1.72 0 00.93-2.591c-.486-.823.262-1.932 1.085-1.446a1.72 1.72 0 002.59-.93z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Pie Chart -->
        <div class="w-full lg:w-1/2">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md h-full">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Approval Workflow</h2>
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
    document.addEventListener('DOMContentLoaded', function () {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#f3f4f6' : '#374151';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
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
                        isDarkMode ? 'rgba(156, 163, 175, 0.7)' : 'rgba(156, 163, 175, 0.7)',
                        isDarkMode ? 'rgba(59, 130, 246, 0.7)' : 'rgba(59, 130, 246, 0.7)',
                        isDarkMode ? 'rgba(16, 185, 129, 0.7)' : 'rgba(16, 185, 129, 0.7)'
                    ],
                    borderColor: [
                        isDarkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(156, 163, 175, 1)',
                        isDarkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(59, 130, 246, 1)',
                        isDarkMode ? 'rgba(255, 255, 255, 0.2)' : 'rgba(16, 185, 129, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        },
                        bodyColor: textColor,
                        titleColor: textColor,
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        borderColor: isDarkMode ? '#4b5563' : '#e5e7eb',
                        borderWidth: 1
                    }
                }
            }
        });

        // Create legend manually
        const legendContainer = document.getElementById('chartLegend');
        ['Pending Pre-Approval', 'Final Approved', 'Completed'].forEach((label, i) => {
            const legendItem = document.createElement('div');
            legendItem.className = 'flex items-center mx-4 my-1';

            const colorBox = document.createElement('div');
            colorBox.className = 'w-4 h-4 rounded-full mr-2';
            colorBox.style.backgroundColor = approvalChart.data.datasets[0].backgroundColor[i];

            const text = document.createElement('span');
            text.className = 'text-sm dark:text-gray-300';
            text.style.color = textColor;
            text.textContent = `${label} (${approvalChart.data.datasets[0].data[i]})`;

            legendItem.appendChild(colorBox);
            legendItem.appendChild(text);
            legendContainer.appendChild(legendItem);
        });
    });
</script>
@endsection