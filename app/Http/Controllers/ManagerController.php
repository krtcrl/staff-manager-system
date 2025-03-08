<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel; // Ensure this is the correct model for the 'request' table
use App\Models\Notification; // Add the Notification model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ManagerController extends Controller
{
    public function index()
    {
        // Get the logged-in manager's number
        $managerNumber = Auth::guard('manager')->user()->manager_number;

        // Fetch all requests with the required fields
        $requests = RequestModel::select(
            'unique_code',
            'description',
            'manager_1_status',
            'manager_2_status',
            'manager_3_status',
            'manager_4_status'
        )->get();

        // Fetch unread notifications for the logged-in manager
        $notifications = Notification::where('user_id', Auth::guard('manager')->id())
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Count new requests added today
        $newRequestsToday = RequestModel::whereDate('created_at', today())->count();

        // Count pending requests for the specific manager
        $pendingRequests = RequestModel::where("manager_{$managerNumber}_status", 'pending')->count();

        // Pass the data to the view
        return view('manager.manager_main', compact('requests', 'notifications', 'newRequestsToday', 'pendingRequests'));
    }

    public function show($unique_code)
    {
        // Fetch the request details by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->first();

        // If the request is not found, return a 404 error
        if (!$request) {
            abort(404);
        }

        // Calculate the number of managers who have approved, rejected, or marked the request as pending
        $approvedManagers = [];
        $rejectedManagers = [];
        $pendingManagers = [];

        for ($i = 1; $i <= 4; $i++) {
            $status = $request->{'manager_' . $i . '_status'};
            if ($status === 'approved') {
                $approvedManagers[] = 'Manager ' . $i;
            } elseif ($status === 'rejected') {
                $rejectedManagers[] = 'Manager ' . $i;
            } else {
                $pendingManagers[] = 'Manager ' . $i;
            }
        }

        // Pass the request details and manager status counts to the view
        return view('manager.request_details', compact('request', 'approvedManagers', 'rejectedManagers', 'pendingManagers'));
    }

    public function approve(Request $request, $unique_code)
    {
        
        // Get the logged-in manager
        $manager = Auth::guard('manager')->user();

        // Find the request by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->firstOrFail();

        // Update the manager's status based on their manager_number
        $column = 'manager_' . $manager->manager_number . '_status';
        $request->update([$column => 'approved']);

           // Log the update
    Log::info("Request {$request->unique_code} approved by Manager {$manager->manager_number}. Column updated: {$column}");

        // Broadcast the update to Pusher
        $this->broadcastStatusUpdate($request);

        return redirect()->back()->with('success', 'Request approved by manager ' . $manager->manager_number . '!');
    }

    public function reject(Request $request, $unique_code)
    {
        // Get the logged-in manager
        $manager = Auth::guard('manager')->user();

        // Find the request by unique_code
        $request = RequestModel::where('unique_code', $unique_code)->firstOrFail();

        // Update the manager's status based on their manager_number
        $column = 'manager_' . $manager->manager_number . '_status';
        $request->update([$column => 'rejected']);

           // Log the update
    Log::info("Request {$request->unique_code} rejected by Manager {$manager->manager_number}. Column updated: {$column}");
        // Broadcast the update to Pusher
        $this->broadcastStatusUpdate($request);

        return redirect()->back()->with('success', 'Request rejected by manager ' . $manager->manager_number . '!');
    }

    private function broadcastStatusUpdate($request)
    {
        // Initialize Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        // Broadcast the status update
        $pusher->trigger('requests-channel', 'status-updated', [
            'request' => $request,
        ]);
    }
}