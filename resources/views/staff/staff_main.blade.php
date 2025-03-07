@extends('layouts.staff')

@section('content')
    <h1 class="text-2xl font-semibold">Welcome, {{ Auth::guard('staff')->user()->name }}!</h1>
    <p class="mt-4">You are logged in as a staff member.</p>

    <!-- Display Requests -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Request List</h2>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Unique Code</th>
                    <th class="py-2 px-4 border-b">Description</th>
                    <th class="py-2 px-4 border-b">Manager 1</th>
                    <th class="py-2 px-4 border-b">Manager 2</th>
                    <th class="py-2 px-4 border-b">Manager 3</th>
                    <th class="py-2 px-4 border-b">Manager 4</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr>
                        <td class="py-2 px-4 border-b text-center">
                            <!-- Make Unique Code a link -->
                            <a href="{{ route('staff.request.details', $request->unique_code) }}" class="text-blue-500 hover:underline">
                                {{ $request->unique_code }}
                            </a>
                        </td>
                        <td class="py-2 px-4 border-b text-center">{{ $request->description }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_1_status === 'approved')
                                <span class="text-green-500">✔️</span> <!-- Checkmark for approved -->
                            @elseif($request->manager_1_status === 'rejected')
                                <span class="text-red-500">❌</span> <!-- Cross for rejected -->
                            @else
                                <span class="text-gray-500">⏳</span> <!-- Hourglass for pending -->
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_2_status === 'approved')
                                <span class="text-green-500">✔️</span> <!-- Checkmark for approved -->
                            @elseif($request->manager_2_status === 'rejected')
                                <span class="text-red-500">❌</span> <!-- Cross for rejected -->
                            @else
                                <span class="text-gray-500">⏳</span> <!-- Hourglass for pending -->
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_3_status === 'approved')
                                <span class="text-green-500">✔️</span> <!-- Checkmark for approved -->
                            @elseif($request->manager_3_status === 'rejected')
                                <span class="text-red-500">❌</span> <!-- Cross for rejected -->
                            @else
                                <span class="text-gray-500">⏳</span> <!-- Hourglass for pending -->
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            @if($request->manager_4_status === 'approved')
                                <span class="text-green-500">✔️</span> <!-- Checkmark for approved -->
                            @elseif($request->manager_4_status === 'rejected')
                                <span class="text-red-500">❌</span> <!-- Cross for rejected -->
                            @else
                                <span class="text-gray-500">⏳</span> <!-- Hourglass for pending -->
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection