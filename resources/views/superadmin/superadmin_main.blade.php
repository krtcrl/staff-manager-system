@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Modern Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
            <p class="text-sm text-gray-500">Last updated: {{ now()->format('M j, g:i a') }}</p>
        </div>
    </div>

    <!-- Main Grid - Modern Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <!-- Staff Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Staff</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $staffCount }}</p>
                </div>
                <div class="p-2 bg-purple-100/50 rounded-lg text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Managers Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Managers</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $managersCount }}</p>
                </div>
                <div class="p-2 bg-amber-100/50 rounded-lg text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Parts Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Parts</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $partsCount }}</p>
                </div>
                <div class="p-2 bg-blue-100/50 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Processes Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Processes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $partProcessesCount }}</p>
                </div>
                <div class="p-2 bg-orange-100/50 rounded-lg text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h4v12H4V6zm6 0h4v12h-4V6zm6 0h4v12h-4V6z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Larger Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <!-- Requests Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $requestsCount }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Pending</span>
                    </div>
                </div>
                <div class="p-3 bg-green-100/30 rounded-lg text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Final Requests Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Final Approvals</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $finalRequestsCount }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full">Awaiting</span>
                    </div>
                </div>
                <div class="p-3 bg-indigo-100/30 rounded-lg text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Card -->
        <div class="bg-white p-5 rounded-xl shadow-xs border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $requestHistoriesCount }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">History</span>
                    </div>
                </div>
                <div class="p-3 bg-red-100/30 rounded-lg text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection