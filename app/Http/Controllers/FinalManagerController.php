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

        // Check if all managers have approved (to determine if this is the final approval)
        $allApproved = true;
        foreach ($managerToStatusMapping as $column) {
            if ($finalRequest->$column !== 'approved') {
                $allApproved = false;
                break;
            }
        }

        // Send notification to the staff member who created the request
        // Only notify if this isn't the final approval that completes the process
        if (!$allApproved && $finalRequest->staff) {
            $finalRequest->staff->notify(new \App\Notifications\StaffNotification([
                'title' => 'Request Approved by Final Manager',
                'message' => "Your request {$finalRequest->unique_code} has been approved by Manager {$managerNumber}",
                'url' => route('staff.final.details', $finalRequest->unique_code),
                'type' => 'approval'
            ]));
        }

        // Find and notify next manager in sequence
        $finalManagerNumbers = array_keys($managerToStatusMapping); // [1, 5, 6, 7, 8, 9]
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

        // ✅ Manager status mapping
        $managerToStatusMapping = [
            1 => 'manager_1_status', 
            5 => 'manager_2_status', 
            6 => 'manager_3_status', 
            7 => 'manager_4_status', 
            8 => 'manager_5_status', 
            9 => 'manager_6_status',
        ];

        if (!array_key_exists($managerNumber, $managerToStatusMapping)) {
            return redirect()->back()->with('error', 'You are not authorized to reject this request.');
        }

        $statusColumn = $managerToStatusMapping[$managerNumber];

        // ✅ Find the request
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->firstOrFail();

        // ✅ Update manager status
        $finalRequest->$statusColumn = 'rejected';

        // ✅ Store rejection reason in the description field
        $rejectionReason = $request->input('rejection_reason');
        $finalRequest->description = "[Rejected by Manager $managerNumber: $rejectionReason]";

        $finalRequest->save();

        // ✅ Log the activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'rejection',
            'description' => "Final approval request {$finalRequest->unique_code} rejected. Reason: $rejectionReason",
            'request_type' => 'final-approval',
            'request_id' => $finalRequest->unique_code,
            'created_at' => now(),
        ]);

        // ✅ Broadcast the new activity
        $this->broadcastNewActivity($activity);

        // ✅ Broadcast status update
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
    public function update(Request $request, $id)
{
    try {
        // ✅ Find the request by ID
        $finalRequest = FinalRequest::findOrFail($id);

        // ✅ Validate the request data
        $validatedData = $request->validate([
            'unique_code'    => 'nullable|string',
            'part_number'    => 'nullable|string',
            'part_name'      => 'required|string|max:255',
            'description'    => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
            'is_edited'      => 'nullable|boolean'  // Ensure validation of is_edited
        ]);

        // ✅ Handle attachment update if a new file is uploaded
        if ($request->hasFile('attachment')) {
            $originalFileName = $request->file('attachment')->getClientOriginalName();

            // Delete old attachment if it exists
            if ($finalRequest->attachment) {
                Storage::disk('public')->delete('attachments/' . $finalRequest->attachment);
            }

            // Store the new file
            $path = $request->file('attachment')->storeAs('attachments', $originalFileName, 'public');
            $finalRequest->attachment = $originalFileName;
        }

        // ✅ Update the fields
        $finalRequest->unique_code = $validatedData['unique_code'] ?? $finalRequest->unique_code;
        $finalRequest->part_number = $validatedData['part_number'] ?? $finalRequest->part_number;
        $finalRequest->part_name = $validatedData['part_name'];
        $finalRequest->description = $validatedData['description'] ?? $finalRequest->description;

        // ✅ Reset rejected manager statuses to pending
        for ($i = 1; $i <= 6; $i++) {
            $statusColumn = "manager_{$i}_status";

            if ($finalRequest->$statusColumn === 'rejected') {
                $finalRequest->$statusColumn = 'pending';
            }
        }

        // ✅ Set `is_edited` to true
        $finalRequest->is_edited = true;

        // ✅ Save the updated request
        $finalRequest->save();

        // ✅ Redirect with success message
        return redirect()->route('staff.finalrequests.show', $id)
                         ->with('success', 'Request updated successfully!');

    } catch (\Exception $e) {
        Log::error('Error updating final request:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}
    
}