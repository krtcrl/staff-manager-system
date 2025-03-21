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
        // Debugging: Check if .env variables are being loaded
        Log::info('Pusher credentials from .env:', [
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'cluster' => env('PUSHER_APP_CLUSTER'),
        ]);

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
        // Start a database transaction
        DB::beginTransaction();

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

            // Identify the correct table based on manager number
            if (in_array($managerNumber, [1, 2, 3, 4])) {
                $requestModel = RequestModel::where('unique_code', $unique_code)->firstOrFail();
            } else {
                $requestModel = FinalRequest::where('unique_code', $unique_code)->firstOrFail();
            }

            // ✅ Update the approval status
            $statusColumn = $managerToStatusMapping[$managerNumber];
            $requestModel->$statusColumn = 'approved';
            $requestModel->save();

            // ✅ Log the approval activity
            $activity = Activity::create([
                'manager_id' => $manager->id,
                'type' => 'approval',
                'description' => "Request {$requestModel->unique_code} approved by Manager $managerNumber.",
                'request_type' => in_array($managerNumber, [1, 2, 3, 4]) ? 'pre-approval' : 'final-approval',
                'request_id' => $requestModel->unique_code,
                'created_at' => now(),
            ]);

            // ✅ Broadcast the new activity
            $this->broadcastNewActivity($activity);

            // ✅ Check if all managers have approved
            $allApproved = true;
            $totalManagers = in_array($managerNumber, [1, 2, 3, 4]) ? 4 : 6;

            for ($i = 1; $i <= $totalManagers; $i++) {
                $statusColumn = $managerToStatusMapping[$i];
                if ($requestModel->$statusColumn !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }

            if ($allApproved) {
                Log::info("All managers have approved the request {$requestModel->unique_code}.");

                // ✅ Reset manager statuses to 'pending'
                for ($i = 1; $i <= $totalManagers; $i++) {
                    $statusColumn = $managerToStatusMapping[$i];
                    $requestModel->$statusColumn = 'pending';
                }

                // ✅ Move request to `finalrequests` if no further process
                $finalRequestData = $requestModel->toArray();
                unset($finalRequestData['id']); // Avoid ID conflict

                // ✅ Insert into `finalrequests` table
                FinalRequest::create($finalRequestData);

                // ✅ Remove from `requests` table
                $requestModel->delete();

                Log::info("Request {$requestModel->unique_code} moved to `finalrequests`.");

                // ✅ Commit the transaction
                DB::commit();

                // ✅ Redirect to the request list with a success message
                return redirect()->route('manager.request_list')
                    ->with('success', 'All process types have been completed. The request has been moved to final requests.');
            }

            // ✅ Broadcast status update
            $this->broadcastStatusUpdate($requestModel);

            // ✅ Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Request approved successfully.');
        } catch (\Exception $e) {
            // ✅ Rollback the transaction in case of an error
            DB::rollBack();

            Log::error('Error in approval process:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred while approving. No changes were made.');
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
        // Debugging: Check if Pusher credentials are being retrieved correctly
        $pusherKey = env('PUSHER_APP_KEY');
        $pusherSecret = env('PUSHER_APP_SECRET');
        $pusherAppId = env('PUSHER_APP_ID');
        $pusherCluster = env('PUSHER_APP_CLUSTER');

        if (is_null($pusherKey) || is_null($pusherSecret) || is_null($pusherAppId) || is_null($pusherCluster)) {
            Log::error('Pusher credentials are missing or invalid:', [
                'key' => $pusherKey,
                'secret' => $pusherSecret,
                'app_id' => $pusherAppId,
                'cluster' => $pusherCluster,
            ]);
            return;
        }

        // Initialize Pusher
        $pusher = new Pusher(
            $pusherKey,
            $pusherSecret,
            $pusherAppId,
            [
                'cluster' => $pusherCluster,
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
            ],
        ]);
    }

    private function broadcastNewActivity($activity)
    {
        // Debugging: Check if Pusher credentials are being retrieved correctly
        $pusherKey = env('PUSHER_APP_KEY');
        $pusherSecret = env('PUSHER_APP_SECRET');
        $pusherAppId = env('PUSHER_APP_ID');
        $pusherCluster = env('PUSHER_APP_CLUSTER');

        if (is_null($pusherKey) || is_null($pusherSecret) || is_null($pusherAppId) || is_null($pusherCluster)) {
            Log::error('Pusher credentials are missing or invalid:', [
                'key' => $pusherKey,
                'secret' => $pusherSecret,
                'app_id' => $pusherAppId,
                'cluster' => $pusherCluster,
            ]);
            return;
        }

        // Initialize Pusher
        $pusher = new Pusher(
            $pusherKey,
            $pusherSecret,
            $pusherAppId,
            [
                'cluster' => $pusherCluster,
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