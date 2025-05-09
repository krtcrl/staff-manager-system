@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6 h-full">

        <!-- Left Column -->
        <div class="w-full lg:w-1/2 flex flex-col gap-6 overflow-auto">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Total Staff -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Staff</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $staffCount }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full text-purple-600 dark:text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 4a4 4 0 11-8 0 4 4 0 018 0zM12 14a4 4 0 00-4-4H8a4 4 0 00-4 4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Managers -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Managers</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $managersCount }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full text-yellow-600 dark:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h2.586A2 2 0 0011 4h2a2 2 0 001.414.586H17a2 2 0 012 2v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex-1">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Requests Submitted Per Month</h2>
                <div class="h-[300px] overflow-hidden">
                    <canvas id="monthlyRequestChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col gap-6">
    <!-- Pie Chart -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex-1">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Approval Workflow</h2>
        <div class="h-[300px] overflow-hidden">
            <canvas id="approvalFlowChart" class="w-full h-full"></canvas>
        </div>
        <!-- Chart Legend will be populated dynamically below -->
        <div id="chartLegend" class="mt-4 flex flex-wrap justify-center"></div>
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
                labels: ['Pending Request', 'Final Approval', 'Completed'],
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
        ['Pending Request', 'Final Approval', 'Completed'].forEach((label, i) => {
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

    document.addEventListener('DOMContentLoaded', function () {
    const isDarkMode = document.documentElement.classList.contains('dark');
    const textColor = isDarkMode ? '#f3f4f6' : '#374151';
    const gridColor = isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';

    const monthlyCtx = document.getElementById('monthlyRequestChart').getContext('2d');

    // Convert all data to NUMBERS (in case they're strings)
    const requestsData = {!! json_encode($formattedMonthlyCounts) !!}.map(Number);
    const finalRequestsData = {!! json_encode($formattedMonthlyFinalCounts) !!}.map(Number);
    const historyData = {!! json_encode($formattedMonthlyHistoryCounts) !!}.map(Number);

    // Find the maximum value in all datasets
    const maxValue = Math.max(
        ...requestsData,
        ...finalRequestsData,
        ...historyData
    );

    // Calculate appropriate step size
    let stepSize = 1;
    if (maxValue > 10) stepSize = 2;
    if (maxValue > 20) stepSize = 5;
    if (maxValue > 50) stepSize = 10;

    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Requests',
                    data: requestsData,
                    backgroundColor: isDarkMode ? 'rgba(156, 163, 175, 0.7)' : 'rgba(156, 163, 175, 0.7)',
                    borderColor: 'rgba(156, 163, 175, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Final Requests',
                    data: finalRequestsData,
                    backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.7)' : 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Request History',
                    data: historyData,
                    backgroundColor: isDarkMode ? 'rgba(16, 185, 129, 0.7)' : 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                },
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: Math.max(10, maxValue + stepSize), // Ensure minimum 0-10 scale
                    ticks: {
                        color: textColor,
                        precision: 0,
                        stepSize: stepSize,
                        callback: function(value) {
                            // Only show whole numbers
                            if (value % 1 === 0) {
                                return value;
                            }
                        }
                    },
                    grid: { color: gridColor }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
});


</script>
@endsection