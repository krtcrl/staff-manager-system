<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel; // Ensure this is the correct model for the 'request' table
use App\Models\Notification; // Add the Notification model
use App\Models\Activity; // Add the Activity model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\FinalRequest;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ManagerController extends Controller
{
    public function index()
    {
        $managerNumber = Auth::guard('manager')->user()->manager_number;
        $managerId = Auth::guard('manager')->user()->manager_number;
    
        // ✅ Fetch requests ordered by latest created_at
        $requests = RequestModel::select(
            'unique_code',
            'description',
            'manager_1_status',
            'manager_2_status',
            'manager_3_status',
            'manager_4_status',
            'created_at' // Make sure created_at is included
        )->orderBy('created_at', 'desc')->get(); // 👈 Sort by newest first
    
        // Fetch unread notifications
        $notifications = Notification::where('user_id', Auth::guard('manager')->id())
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    
        $newRequestsToday = RequestModel::whereDate('created_at', today())->count();
        $pendingRequests = RequestModel::where("manager_{$managerNumber}_status", 'pending')->count();
        $recentActivities = Activity::where('manager_id', $managerId)->orderBy('created_at', 'desc')->get();
    
        return view('manager.manager_main', compact('requests', 'notifications', 'newRequestsToday', 'pendingRequests', 'recentActivities'));
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
        try {
            $manager = Auth::guard('manager')->user();
            $requestModel = RequestModel::where('unique_code', $unique_code)->firstOrFail();
    
            // Update the manager's approval status
            $column = 'manager_' . $manager->manager_number . '_status';
            $requestModel->$column = 'approved';
            $requestModel->save();
    
            // Log the approval activity
            $activity = Activity::create([
                'manager_id' => $manager->id,
                'type' => 'approval',
                'description' => "Request {$requestModel->unique_code} approved by Manager {$manager->manager_number}.",
                'expires_at' => now()->addHours(24),
            ]);
            $this->broadcastNewActivity($activity);
    
            Log::info("Request {$requestModel->unique_code} approved by Manager {$manager->manager_number}.");
    
            // ✅ Check if all managers have approved
            if (
                $requestModel->manager_1_status === 'approved' &&
                $requestModel->manager_2_status === 'approved' &&
                $requestModel->manager_3_status === 'approved' &&
                $requestModel->manager_4_status === 'approved'
            ) {
                Log::info("All managers approved. Checking for next process...");
    
                // ✅ Get the next process based on process_order
                $nextProcess = DB::table('part_processes')
                    ->where('part_number', $requestModel->part_number)
                    ->where('process_order', '>', $requestModel->current_process_index)
                    ->orderBy('process_order')
                    ->first();
    
                if ($nextProcess) {
                    Log::info("Next process found: {$nextProcess->process_type} (Order: {$nextProcess->process_order})");
    
                    // ✅ Update to the next process
                    $requestModel->process_type = $nextProcess->process_type;
                    $requestModel->current_process_index = $nextProcess->process_order;
    
                    // Reset all managers to pending
                    $requestModel->manager_1_status = 'pending';
                    $requestModel->manager_2_status = 'pending';
                    $requestModel->manager_3_status = 'pending';
                    $requestModel->manager_4_status = 'pending';
    
                    Log::info("Request {$requestModel->unique_code} moved to process: {$nextProcess->process_type}");
                } else {
                    // ✅ If no next process, mark request as completed
                    Log::info("Request {$requestModel->unique_code} completed. Moving to finalrequests table...");
    
                    // Move the request to the finalrequests table
                    FinalRequest::create([
                        'unique_code' => $requestModel->unique_code,
                        'part_number' => $requestModel->part_number,
                        'part_name' => $requestModel->part_name,
                        'revision_type' => $requestModel->revision_type,
                        'uph' => $requestModel->uph,
                        'description' => $requestModel->description,
                        'attachment' => $requestModel->attachment,
                        'manager_1_status' => $requestModel->manager_1_status,
                        'manager_2_status' => $requestModel->manager_2_status,
                        'manager_3_status' => $requestModel->manager_3_status,
                        'manager_4_status' => $requestModel->manager_4_status,
                        'process_type' => $requestModel->process_type,
                        'current_process_index' => $requestModel->current_process_index,
                        'total_processes' => $requestModel->total_processes,
                    ]);
    
                    // Delete the request from the requests table
                    $requestModel->delete();
    
                    Log::info("Request {$requestModel->unique_code} successfully moved to finalrequests and deleted from requests.");
                    
                    // Broadcast the status update
                    $this->broadcastStatusUpdate($requestModel);
    
                    // ✅ Redirect back to the request list
                    return redirect()->route('manager.requestList')->with('success', 'Request fully approved and moved to final requests.');
                }
    
                // ✅ Save updated request
                $requestModel->save();
                Log::info("Final request status saved successfully.");
            }
    
            // ✅ Broadcast status update
            $this->broadcastStatusUpdate($requestModel);
    
            return redirect()->back()->with('success', 'Request approved successfully!');
        } catch (\Exception $e) {
            Log::error('Error in approval process:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()->back()->with('error', 'An error occurred while approving.');
        }
    }
    
    
    public function reject(Request $request, $unique_code)
    {
        try {
            $manager = Auth::guard('manager')->user();
            $requestModel = RequestModel::where('unique_code', $unique_code)->firstOrFail();
    
            // Update the manager's status to 'rejected'
            $statusColumn = 'manager_' . $manager->manager_number . '_status';
            $requestModel->$statusColumn = 'rejected';
    
            // Replace the description with only the new rejection reason
            $rejectionReason = $request->input('rejection_reason');
            $requestModel->description = "Rejected by Manager {$manager->manager_number}: {$rejectionReason}";
    
            $requestModel->save();
    
            // Log the activity
            $activity = Activity::create([
                'manager_id' => $manager->id,
                'type' => 'rejection',
                'description' => "Request {$requestModel->unique_code} rejected by Manager {$manager->manager_number}. Reason: {$rejectionReason}",
                'expires_at' => now()->addHours(24),
            ]);
    
            // Broadcast the activity update
            $this->broadcastNewActivity($activity);
            $this->broadcastStatusUpdate($requestModel);
    
            return redirect()->back()->with('success', 'Request rejected successfully!');
        } catch (\Exception $e) {
            Log::error('Error rejecting request:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()->back()->with('error', 'An error occurred while rejecting.');
        }
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

    private function broadcastNewActivity($activity)
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
    
        // Broadcast the new activity
        $pusher->trigger('activities-channel', 'new-activity', [
            'activity' => $activity,
        ]);
    }
    public function requestList()
    {
        // ✅ Ensure newest requests stay at the top even after refresh
        $requests = RequestModel::orderBy('created_at', 'desc')->paginate(10); // 👈 Sort by newest first
    
        return view('manager.request_list', compact('requests'));
    }
    
}