@extends('layouts.superadmin')

@section('content')
<div class="container mx-auto px-4 py-6">


    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Requests Card -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $requestsCount }}</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                    </svg>
                </div>
            </div>
        </div>
<!-- Final Requests Card -->
<div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-green-500">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Total Final Requests</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $finalRequestsCount }}</p>
        </div>
        <div class="p-3 bg-green-100 rounded-full text-green-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M16.862 3.487a2.25 2.25 0 013.182 3.182L10 16.713l-4 1 1-4 9.862-10.226zM4 20c.5-1.5 2-2 4-1.5s3 1.5 5.5.5 2.5-2 4.5-1.5" />
</svg>

        </div>
    </div>
</div>


<!-- Request Histories Card -->
<div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-blue-500">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Request Histories</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $requestHistoriesCount }}</p>
        </div>
        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>


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

        <!-- Total Parts Card -->
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Parts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $partsCount }}</p>
                </div>
                <div class="p-3 bg-teal-100 rounded-full text-teal-600">
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
@endsection
