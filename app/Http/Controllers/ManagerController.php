<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel; // Model for the 'requests' table
use App\Models\FinalRequest; // Model for the 'finalrequests' table
use App\Models\Notification; // Notification model
use App\Models\Activity; // Activity model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ManagerController extends Controller
{
    public function index()
    {
        $managerNumber = Auth::guard('manager')->user()->manager_number;
    
        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status', // Manager 1
            2 => 'manager_2_status', // Manager 2
            3 => 'manager_3_status', // Manager 3
            4 => 'manager_4_status', // Manager 4
            5 => 'manager_2_status', // Manager 5
            6 => 'manager_3_status', // Manager 6
            7 => 'manager_4_status', // Manager 7
            8 => 'manager_5_status', // Manager 8
            9 => 'manager_6_status', // Manager 9
        ];
    
        // Initialize variables
        $requests = collect();
        $pendingRequests = 0;
        $pendingFinalRequests = 0;
    
        // Check if the manager is assigned to a status column
        if (array_key_exists($managerNumber, $managerToStatusMapping)) {
            $statusColumn = $managerToStatusMapping[$managerNumber];
    
            // Determine which table to query based on the manager number
            if (in_array($managerNumber, [1, 2, 3, 4])) {
                // Fetch pending requests for Managers 1-4 from the `requests` table
                $requests = RequestModel::where($statusColumn, 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                $pendingRequests = $requests->count();
            } else {
                // Fetch pending requests for Managers 5-9 from the `finalrequests` table
                $requests = FinalRequest::where($statusColumn, 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                $pendingFinalRequests = $requests->count();
            }
        }
    
        // Fetch unread notifications
        $notifications = Notification::where('user_id', Auth::guard('manager')->id())
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    
        // New requests today
        $newRequestsToday = RequestModel::whereDate('created_at', today())->count();
    
        // Fetch recent activities for the logged-in manager
        $recentActivities = Activity::where('manager_id', Auth::guard('manager')->id())
            ->orderBy('created_at', 'desc')
            ->take(10) // Limit to the last 10 activities
            ->get();
    
        // Pass all necessary variables to the view
        return view('manager.manager_main', compact(
            'requests',
            'notifications',
            'newRequestsToday',
            'pendingRequests',
            'pendingFinalRequests',
            'recentActivities'
        ));
    }

    /**
     * Display the details of a specific request.
     *
     * @param string $unique_code
     * @return \Illuminate\View\View
     */
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
        $managerNumber = $manager->manager_number;

        // Debug: Check manager number and request
        Log::info("Manager {$managerNumber} approving request {$unique_code}");

        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status', // Manager 1
            2 => 'manager_2_status', // Manager 2
            3 => 'manager_3_status', // Manager 3
            4 => 'manager_4_status', // Manager 4
            5 => 'manager_2_status', // Manager 5
            6 => 'manager_3_status', // Manager 6
            7 => 'manager_4_status', // Manager 7
            8 => 'manager_5_status', // Manager 8
            9 => 'manager_6_status', // Manager 9
        ];

        // Determine which table to query based on the manager number
        if (in_array($managerNumber, [1, 2, 3, 4])) {
            $requestModel = RequestModel::where('unique_code', $unique_code)->firstOrFail();
        } else {
            $requestModel = FinalRequest::where('unique_code', $unique_code)->firstOrFail();
        }

        // Debug: Check request model
        Log::info("Request model found: ", ['request' => $requestModel->toArray()]);

        // Update the manager's approval status
        $statusColumn = $managerToStatusMapping[$managerNumber];
        $requestModel->$statusColumn = 'approved';
        $requestModel->save();

        // Log the approval activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'approval',
            'description' => "Pre-approval request {$requestModel->unique_code} approved.",
            'request_type' => 'pre-approval',
            'request_id' => $requestModel->unique_code,
            'created_at' => now(),
        ]);

        // Broadcast the new activity
        $this->broadcastNewActivity($activity);

        Log::info("Pre-approval request {$requestModel->unique_code} approved.");

        // Check if all pre-approval managers (Managers 1, 2, 3, and 4) have approved
        $allApproved = true;
        for ($i = 1; $i <= 4; $i++) {
            $statusColumn = $managerToStatusMapping[$i];
            if ($requestModel->$statusColumn !== 'approved') {
                $allApproved = false;
                break;
            }
        }

        if ($allApproved) {
            // Debug: All pre-approval managers have approved
            Log::info("All pre-approval managers have approved the request {$requestModel->unique_code}.");

            // Reset the statuses of the pre-approval managers (Managers 1, 2, 3, and 4)
            for ($i = 1; $i <= 4; $i++) {
                $statusColumn = $managerToStatusMapping[$i];
                $requestModel->$statusColumn = 'pending'; // Reset to pending
            }

            // Fetch the next process_type based on current_process_index from part_processes table
            $currentProcessIndex = $requestModel->current_process_index ?? 1; // Default to 1 if NULL
            Log::info("Current process index: ", ['current_process_index' => $currentProcessIndex]);

            $partProcess = DB::table('part_processes')
                ->where('part_number', $requestModel->part_number)
                ->where('process_order', '>', $currentProcessIndex)
                ->orderBy('process_order', 'asc')
                ->first();

            Log::info("Next process: ", ['next_process' => $partProcess ? (array) $partProcess : 'No next process found']);

            if ($partProcess) {
                // Update the process_type and current_process_index
                $requestModel->process_type = $partProcess->process_type;
                $requestModel->current_process_index = $partProcess->process_order;

                // Update the progress (e.g., "1/4")
                $totalProcesses = DB::table('part_processes')
                    ->where('part_number', $requestModel->part_number)
                    ->count();
                $requestModel->progress = "{$partProcess->process_order}/{$totalProcesses}";

                // Save the updated request
                $requestModel->save();

                Log::info("Request {$requestModel->unique_code} progressed to next stage: {$requestModel->process_type}.");
            } else {
                // If there are no more processes, move the request to the finalrequests table
                $finalRequestData = $requestModel->toArray();
                unset($finalRequestData['id']); // Remove the ID to avoid conflicts

                // Insert the request into the finalrequests table
                FinalRequest::create($finalRequestData);

                // Delete the request from the requests table
                $requestModel->delete();

                Log::info("Request {$requestModel->unique_code} moved to finalrequests table and deleted from requests table.");
            }
        }

        // Broadcast status update
        $this->broadcastStatusUpdate($requestModel);

        return redirect()->back()->with('success', 'Request approved successfully.');
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
        $managerNumber = $manager->manager_number;

        // Mapping of manager numbers to status columns
        $managerToStatusMapping = [
            1 => 'manager_1_status',
            2 => 'manager_2_status',
            3 => 'manager_3_status',
            4 => 'manager_4_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status',
        ];

        // Determine which table to query based on the manager number
        if (in_array($managerNumber, [1, 2, 3, 4])) {
            $requestModel = RequestModel::where('unique_code', $unique_code)->firstOrFail();
        } else {
            $requestModel = FinalRequest::where('unique_code', $unique_code)->firstOrFail();
        }

        // Update the manager's status to 'rejected'
        $statusColumn = $managerToStatusMapping[$managerNumber];
        $requestModel->$statusColumn = 'rejected';

        // Replace the description with the rejection reason
        $rejectionReason = $request->input('rejection_reason');
        $requestModel->description = "[Rejected by Manager $managerNumber: $rejectionReason]";

        $requestModel->save();

        // Log the rejection activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'rejection',
            'description' => "Pre-approval request {$requestModel->unique_code} rejected. Reason: $rejectionReason",
            'request_type' => 'pre-approval',
            'request_id' => $requestModel->unique_code,
            'created_at' => now(),
        ]);

        // Broadcast the new activity
        $this->broadcastNewActivity($activity);

        Log::info("Pre-approval request {$requestModel->unique_code} rejected by Manager $managerNumber.");

        // Broadcast status update
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

    /**
     * Broadcast the status update using Pusher.
     *
     * @param mixed $request
     */
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
            'request' => [
                'unique_code' => $request->unique_code,
                'manager_1_status' => $request->manager_1_status,
                'manager_2_status' => $request->manager_2_status,
                'manager_3_status' => $request->manager_3_status,
                'manager_4_status' => $request->manager_4_status,
                'process_type' => $request->process_type,
                'current_process_index' => $request->current_process_index,
                'total_processes' => $request->total_processes,
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
            'description' => $activity->description, // Include the description
            'created_at' => $activity->created_at,
        ],
    ]);
}

    /**
     * Display the list of requests.
     *
     * @return \Illuminate\View\View
     */
    public function requestList()
    {
        // Fetch all requests sorted by creation date
        $requests = RequestModel::orderBy('created_at', 'desc')->paginate(10);

        return view('manager.request_list', compact('requests'));
    }

    /**
     * Display the list of final requests.
     *
     * @return \Illuminate\View\View
     */
    public function finalRequestList()
    {
        // Fetch all final requests sorted by creation date
        $finalRequests = FinalRequest::orderBy('created_at', 'desc')->paginate(10);

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

        // Pass the final request details to the view
        return view('manager.finalrequest_details', compact('finalRequest'));
    }
}