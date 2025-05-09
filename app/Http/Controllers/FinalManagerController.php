<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;
use Pusher\Pusher;
use App\Models\Staff;
use App\Models\Manager;
use Illuminate\Support\Facades\DB;
use App\Notifications\ApprovalNotification; // Add this line

use App\Notifications\FinalApprovalNotification;


class FinalManagerController extends Controller
{
    /**
     * Display the Final Request List.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the logged-in manager's number
        $managerNumber = Auth::guard('manager')->user()->manager_number;

        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status', // Manager 1
            5 => 'manager_2_status', // Manager 5
            6 => 'manager_3_status', // Manager 6
            7 => 'manager_4_status', // Manager 7
            8 => 'manager_5_status', // Manager 8
            9 => 'manager_6_status', // Manager 9
        ];

        // Fetch final requests for the logged-in manager
        if (array_key_exists($managerNumber, $managerToStatusMapping)) {
            $statusColumn = $managerToStatusMapping[$managerNumber];
            $finalRequests = FinalRequest::where($statusColumn, 'pending')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $finalRequests = collect(); // No final requests for this manager
        }

        return view('manager.finalrequest_list', compact('finalRequests'));
    }

    /**
     * Display the details of a specific final request.
     *
     * @param string $unique_code
     * @return \Illuminate\View\View
     */
    public function finalRequestDetails($unique_code)
{
    // Fetch the final request details by unique_code
    $finalRequest = FinalRequest::where('unique_code', $unique_code)->first();

    // If the final request is not found, return a 404 error
    if (!$finalRequest) {
        abort(404);
    }

    // Initialize manager status arrays
    $approvedManagers = [];
    $rejectedManagers = [];
    $pendingManagers = [];

    // Loop through 6 managers (adjust this number based on your database schema)
    for ($i = 1; $i <= 6; $i++) {
        $statusColumn = 'manager_' . $i . '_status';
        
        // Check if the status column exists in the final request
        if (!isset($finalRequest->$statusColumn)) {
            \Log::warning("Status column $statusColumn does not exist in final request.");
            continue;
        }

        $status = $finalRequest->$statusColumn;
        \Log::info("Manager $i Status: $status"); // Debugging: Log each manager's status

        if ($status === 'approved') {
            $approvedManagers[] = 'Manager ' . $i;
        } elseif ($status === 'rejected') {
            $rejectedManagers[] = 'Manager ' . $i;
        } else {
            $pendingManagers[] = 'Manager ' . $i;
        }
    }

    // Debugging: Log the manager status arrays
    \Log::info('Approved Managers:', $approvedManagers);
    \Log::info('Rejected Managers:', $rejectedManagers);
    \Log::info('Pending Managers:', $pendingManagers);

    // Pass the final request details and manager status counts to the view
    return view('manager.finalrequest_details', compact('finalRequest', 'approvedManagers', 'rejectedManagers', 'pendingManagers'));
}


public function approveFinalRequest(Request $request, $unique_code)
{
    DB::beginTransaction(); // Start transaction

    try {
        $manager = Auth::guard('manager')->user();
        $managerNumber = $manager->manager_number;

        Log::info("Manager Number: {$managerNumber} approving request: {$unique_code}");

        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status',
        ];

        if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
            Log::error("Unauthorized manager: {$managerNumber}");
            DB::rollBack(); // Rollback if unauthorized
            return redirect()->back()->with('error', 'You are not authorized to approve this request.');
        }

        $statusColumn = $managerToStatusMapping[$managerNumber];
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

        // Ensure previous managers have approved
        $allPreviousApproved = true;
        foreach ($managerToStatusMapping as $mgrNumber => $column) {
            if ($mgrNumber === $managerNumber) {
                break;
            }
            if ($finalRequest->$column !== 'approved') {
                $allPreviousApproved = false;
                break;
            }
        }

        if (!$allPreviousApproved) {
            Log::warning("Previous managers have not approved.");
            DB::rollBack(); // Rollback if previous approvals missing
            return redirect()->back()->with('error', 'Previous managers must approve first.');
        }

        // Approve the current manager
        $finalRequest->$statusColumn = 'approved';
        $finalRequest->save();

        Log::info("Manager {$managerNumber} approved the request.");

        // Log the approval activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'approval',
            'description' => "Final approval request {$finalRequest->unique_code} approved.",
            'request_type' => 'final-approval',
            'request_id' => $finalRequest->unique_code,
            'created_at' => now(),
        ]);

        // Broadcast the new activity
        $this->broadcastNewActivity($activity);

        // Broadcast status update
        $this->broadcastStatusUpdate($finalRequest);

        // Check if all managers have approved
        $allApproved = true;
        foreach ($managerToStatusMapping as $column) {
            if ($finalRequest->$column !== 'approved') {
                $allApproved = false;
                break;
            }
        }

        // Send notification to the staff member who created the final request
        if (!$allApproved && $finalRequest->staff) {
            $staffData = [
                'title' => 'Request Approved by Final Manager',
                'message' => "Your request {$finalRequest->unique_code} has been approved by Manager {$managerNumber}",
                'url' => route('staff.final.details', $finalRequest->unique_code),
                'type' => 'final_approval',
                'request_id' => $finalRequest->unique_code,
                'manager_number' => $managerNumber,
            ];
            $finalRequest->staff->notify(new \App\Notifications\StaffNotification($staffData));
        }

        // Find and notify next manager in sequence
        $finalManagerNumbers = array_keys($managerToStatusMapping);
        $currentIndex = array_search($managerNumber, $finalManagerNumbers);
        $nextIndex = ($currentIndex + 1) % count($finalManagerNumbers);
        $nextManagerNumber = $finalManagerNumbers[$nextIndex];
        $statusCol = $managerToStatusMapping[$nextManagerNumber];

        // Verify the next manager hasn't already approved
        if ($finalRequest->$statusCol === 'pending') {
            $nextManager = Manager::where('manager_number', $nextManagerNumber)->first();

            if ($nextManager) {
                $nextManager->notify(new ApprovalNotification(
                    $finalRequest,
                    route('manager.finalrequest.details', $finalRequest->unique_code),
                    $nextManagerNumber
                ));

                Log::info("Notified next manager {$nextManagerNumber} about pending approval for request {$finalRequest->unique_code}");
            }
        }

        if ($allApproved) {
            Log::info("All managers approved. Moving request to request_histories.");
            
            $finalRequest->status = 'completed';
            $finalRequest->save();

            try {
                // Insert into request_histories
                DB::table('request_histories')->insert([
                    'unique_code' => $finalRequest->unique_code,
                    'part_number' => $finalRequest->part_number,
                    'part_name' => $finalRequest->part_name,
                    'description' => $finalRequest->description,
                    'manager_1_status' => $finalRequest->manager_1_status,
                    'manager_2_status' => $finalRequest->manager_2_status,
                    'manager_3_status' => $finalRequest->manager_3_status,
                    'manager_4_status' => $finalRequest->manager_4_status,
                    'manager_5_status' => $finalRequest->manager_5_status,
                    'manager_6_status' => $finalRequest->manager_6_status,
                    'staff_id' => $finalRequest->staff_id,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'created_at' => $finalRequest->created_at,
                    'updated_at' => now(),
                ]);

                // Delete from finalrequests
                DB::table('finalrequests')->where('unique_code', $finalRequest->unique_code)->delete();

                // Notify staff about completion
                if ($finalRequest->staff) {
                    $finalRequest->staff->notify(new \App\Notifications\StaffNotification([
                        'title' => 'Request Completed',
                        'message' => "Your request {$finalRequest->unique_code} has been completed and moved to request history",
                        'url' => route('staff.request.history', $finalRequest->unique_code),
                        'type' => 'completed'
                    ]));
                }

                DB::commit(); // Commit all changes
                return redirect()->route('manager.finalrequest-list')
                    ->with('success', "Final request '{$finalRequest->unique_code}' has been fully approved.");
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback if history insertion fails
                Log::error('Failed to move request to history:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Failed to complete the request. Please try again.');
            }
        }

        DB::commit(); // Commit changes for partial approval
        return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
            ->with('success', 'Request approved successfully!');

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback on any error
        Log::error('Error in approval process:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'An error occurred while approving. No changes were made.');
    }
}

public function rejectFinalRequest(Request $request, $unique_code)
{
    DB::beginTransaction(); // Start transaction

    try {
        $manager = Auth::guard('manager')->user();
        $managerNumber = $manager->manager_number;
        $managerName = $manager->name;

        // Manager status mapping
        $managerToStatusMapping = [
            1 => 'manager_1_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status',
        ];

        if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
            DB::rollBack(); // Rollback if unauthorized
            return redirect()->back()->with('error', 'You are not authorized to reject this request.');
        }

        $statusColumn = $managerToStatusMapping[$managerNumber];

        // Find the request
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

        // Get rejection reason before any changes
        $rejectionReason = $request->input('rejection_reason');
        if (empty($rejectionReason)) {
            DB::rollBack(); // Rollback if no reason provided
            return redirect()->back()->with('error', 'Rejection reason is required.');
        }

        // Update manager status and description
        $finalRequest->$statusColumn = 'rejected';
        $finalRequest->description = "[Rejected by Manager $managerNumber: $rejectionReason]";
        $finalRequest->save();

        // Log the activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'rejection',
            'description' => "Final approval request {$finalRequest->unique_code} rejected. Reason: $rejectionReason",
            'request_type' => 'final-approval',
            'request_id' => $finalRequest->unique_code,
            'created_at' => now(),
        ]);

        // Broadcast the new activity
        $this->broadcastNewActivity($activity);

        // Broadcast status update
        $this->broadcastStatusUpdate($finalRequest);

        // Notify the staff about the rejection
        if ($finalRequest->staff) {
            $staffData = [
                'request_id' => $finalRequest->unique_code,
                'manager_number' => $managerNumber,
                'url' => route('staff.final.details', $finalRequest->unique_code),
                'type' => 'rejected',
                'message' => "Your request {$finalRequest->unique_code} has been rejected by Final Manager {$managerNumber}. Reason: $rejectionReason",
                'rejection_reason' => $rejectionReason
            ];
            
            try {
                $finalRequest->staff->notify(new \App\Notifications\FinalRejectNotification(
                    $finalRequest, 
                    $staffData['url'], 
                    $managerNumber, 
                    $rejectionReason
                ));
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback if notification fails
                Log::error('Failed to send rejection notification:', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Request was not rejected. Failed to send notification.');
            }
        }

        DB::commit(); // Commit all changes if everything succeeds
        return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
                         ->with('success', 'Request rejected successfully!');
        
    } catch (\Exception $e) {
        DB::rollBack(); // Rollback on any error
        Log::error('Error in rejection process:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'An error occurred while rejecting. No changes were made.');
    }
}





    private function broadcastStatusUpdate($finalRequest)
    {
        $manager = Auth::guard('manager')->user();
        $managerNumber = $manager->manager_number;

        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status', // Manager 1
            5 => 'manager_2_status', // Manager 5
            6 => 'manager_3_status', // Manager 6
            7 => 'manager_4_status', // Manager 7
            8 => 'manager_5_status', // Manager 8
            9 => 'manager_6_status', // Manager 9
        ];

        // Fetch the updated count of pending final approval requests for the specific manager
        $pendingFinalRequests = FinalRequest::where($managerToStatusMapping[$managerNumber], 'pending')
            ->count();

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
        $pusher->trigger('finalrequests-channel', 'status-updated', [
            'finalRequest' => $finalRequest,
            'pendingFinalRequests' => $pendingFinalRequests,
        ]);

        // Broadcast the new activity
        $pusher->trigger('activities-channel', 'new-activity', [
            'activity' => [
                'request_type' => 'final-approval',
                'request_id' => $finalRequest->unique_code,
                'type' => $finalRequest->{$managerToStatusMapping[$managerNumber]} === 'approved' ? 'approval' : 'rejection',
                'description' => $finalRequest->{$managerToStatusMapping[$managerNumber]} === 'approved'
                    ? "Final approval request {$finalRequest->unique_code} approved. {$managerNumber}."
                    : "Final approval request {$finalRequest->unique_code} rejected. {$managerNumber}.",
                'created_at' => now(),
            ],
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
            'activity' => [
                'request_type' => $activity->request_type,
                'request_id' => $activity->request_id,
                'type' => $activity->type,
                'description' => $activity->description,
                'created_at' => $activity->created_at,
            ],
        ]);
    }
   
    
    
}