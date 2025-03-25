<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;
use Pusher\Pusher;

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

        if ($allApproved) {
            Log::info("All managers approved. Moving request to request_histories.");

            $finalRequest->status = 'completed';
            $finalRequest->save();

            \DB::transaction(function () use ($finalRequest) {
                try {
                    // Insert into request_histories with staff_id
                    \DB::table('request_histories')->insert([
                        'unique_code' => $finalRequest->unique_code,
                        'part_number' => $finalRequest->part_number,
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
                    \DB::table('finalrequests')->where('unique_code', $finalRequest->unique_code)->delete();

                    Log::info("Inserted successfully and deleted from finalrequests.");
                } catch (\Exception $e) {
                    Log::error('Transaction failed:', ['error' => $e->getMessage()]);
                    throw $e;
                }
            });

            // 🚀 Redirect to the final request list with success alert
            return redirect()->route('manager.finalrequest-list')
                             ->with('success', "Final request '{$finalRequest->unique_code}' has been fully approved.");
        }

        return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
                         ->with('success', 'Request approved successfully!');

    } catch (\Exception $e) {
        Log::error('Error in approval process:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->back()->with('error', 'An error occurred while approving.');
    }
}



    public function rejectFinalRequest(Request $request, $unique_code)
    {
        try {
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

            if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
                return redirect()->back()->with('error', 'You are not authorized to reject this request.');
            }

            $statusColumn = $managerToStatusMapping[$managerNumber];
            $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

            // Update the manager's status column to 'rejected'
            $finalRequest->$statusColumn = 'rejected';
            $finalRequest->rejection_reason = $request->input('rejection_reason');
            $finalRequest->save();

            // Log the rejection activity
            $activity = Activity::create([
                'manager_id' => $manager->id,
                'type' => 'rejection',
                'description' => "Final approval request {$finalRequest->unique_code} rejected. Reason: {$finalRequest->rejection_reason}", // Removed manager number and status
                'request_type' => 'final-approval', // Add request type
                'request_id' => $finalRequest->unique_code, // Add request ID
                'created_at' => now(),
            ]);

            // Broadcast the new activity
            $this->broadcastNewActivity($activity);

            // Broadcast status update
            $this->broadcastStatusUpdate($finalRequest);

            return redirect()->route('manager.finalrequest.details', ['unique_code' => $finalRequest->unique_code])
                             ->with('success', 'Request rejected successfully!');
        } catch (\Exception $e) {
            Log::error('Error in rejection process:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while rejecting.');
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