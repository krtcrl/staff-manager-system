<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestModel; // Model for the 'requests' table
use App\Models\FinalRequest; // Model for the 'finalrequests' table
use App\Models\Activity; // Activity model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;



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
            'newRequestsToday',
            'pendingRequests',
            'pendingFinalRequests',
            'recentActivities'
        ));
    }
    public function downloadAttachment($filename)
    {
        try {
            // 1. Decode and sanitize the filename
            $decodedFilename = urldecode($filename);
            $cleanFilename = basename($decodedFilename);

            // 2. Build the storage path
            $path = 'attachments/' . $cleanFilename;

            // 3. Verify if the file exists
            if (!Storage::disk('public')->exists($path)) {
                Log::error("Manager attachment not found", [
                    'requested_filename' => $filename,
                    'clean_path' => $path,
                    'storage_files' => Storage::disk('public')->files('attachments')
                ]);
                abort(404, 'File not found');
            }

            // 4. Force download with original filename
            return Storage::disk('public')->download($path, $cleanFilename);

        } catch (\Exception $e) {
            Log::error("Manager attachment download failed", [
                'error' => $e->getMessage(),
                'filename' => $filename ?? 'null'
            ]);
            abort(500, 'Download failed. Please try again.');
        }
    }

    /**
     * Download final approval attachment
     */
    public function downloadFinalAttachment($filename)
    {
        try {
            // 1. Decode and sanitize the filename
            $decodedFilename = urldecode($filename);
            $cleanFilename = basename($decodedFilename);

            // 2. Build the storage path
            $path = 'final_approval_attachments/' . $cleanFilename;

            // 3. Verify if the file exists
            if (!Storage::disk('public')->exists($path)) {
                Log::error("Manager final approval attachment not found", [
                    'requested_filename' => $filename,
                    'clean_path' => $path,
                    'storage_files' => Storage::disk('public')->files('final_approval_attachments')
                ]);
                abort(404, 'File not found');
            }

            // 4. Force download with original filename
            return Storage::disk('public')->download($path, $cleanFilename);

        } catch (\Exception $e) {
            Log::error("Manager final approval attachment download failed", [
                'error' => $e->getMessage(),
                'filename' => $filename ?? 'null'
            ]);
            abort(500, 'Download failed. Please try again.');
        }
    }

    /**
     * Display the details of a specific request.
     *
     * @param string $unique_code
     * @return \Illuminate\View\View
     */
    public function show($unique_code)
    {
        // Fetch the request with all manager statuses
        $request = RequestModel::where('unique_code', $unique_code)
            ->select('*', 
                'manager_1_status as m1_status',
                'manager_2_status as m2_status',
                'manager_3_status as m3_status',
                'manager_4_status as m4_status'
            )
            ->first();
    
        if (!$request) {
            abort(404);
        }
    
        // ✅ Include the `is_edited` flag
        $isEdited = $request->is_edited ?? false;
    
        // Initialize arrays for manager statuses
        $approvedManagers = [];
        $rejectedManagers = [];
        $pendingManagers = [];
    
        // Check if request is already rejected by any manager
        $isRejected = false;
        $rejectingManager = null;
    
        // Process each manager's status
        for ($i = 1; $i <= 4; $i++) {
            $status = $request->{'manager_' . $i . '_status'} ?? 'pending';
    
            if ($status === 'approved') {
                $approvedManagers[] = 'Manager ' . $i;
            } elseif ($status === 'rejected') {
                $rejectedManagers[] = 'Manager ' . $i;
                $isRejected = true;
                $rejectingManager = $i;
            } else {
                // Only show as pending if request isn't already rejected
                if (!$isRejected) {
                    $pendingManagers[] = 'Manager ' . $i;
                }
            }
        }
    
        // Get process type with fallback
        $processType = $request->process_type ?? 'N/A';
    
        // Handle attachments
        $attachments = [];
        if ($request->attachment) {
            $attachments[] = [
                'type' => 'pre-approval',
                'filename' => $request->attachment
            ];
        }
        if ($request->final_approval_attachment) {
            $attachments[] = [
                'type' => 'final-approval',
                'filename' => $request->final_approval_attachment
            ];
        }
    
        // ✅ Pass `is_edited` to the view
        return view('manager.request_details', [
            'request' => $request,
            'approvedManagers' => $approvedManagers,
            'rejectedManagers' => $rejectedManagers,
            'pendingManagers' => $pendingManagers,
            'processType' => $processType,
            'attachments' => $attachments,
            'isRejected' => $isRejected,
            'rejectingManager' => $rejectingManager,
            'isEdited' => $isEdited  // Include the `is_edited` flag in the view
        ]);
    }
    
    public function approve(Request $request, $unique_code)
    {
        DB::beginTransaction();
    
        try {
            $manager = Auth::guard('manager')->user();
            $managerNumber = $manager->manager_number;
    
            // Pre-approval manager status mapping
            $managerToStatusMapping = [
                1 => 'manager_1_status',
                2 => 'manager_2_status',
                3 => 'manager_3_status',
                4 => 'manager_4_status',
            ];
    
            // Identify if pre-approval or final approval
            $isPreApproval = in_array($managerNumber, [1, 2, 3, 4]);
            $requestModel = $isPreApproval 
                ? RequestModel::where('unique_code', $unique_code)->firstOrFail()
                : FinalRequest::where('unique_code', $unique_code)->firstOrFail();
    
            // ✅ Update the current manager's status to approved
            $statusColumn = $managerToStatusMapping[$managerNumber];
            $requestModel->$statusColumn = 'approved';
            $requestModel->save();
    
            // ✅ Log activity
            $activity = Activity::create([
                'manager_id' => $manager->id,
                'type' => 'approval',
                'description' => "Request {$requestModel->unique_code} approved by Manager $managerNumber.",
                'request_type' => $isPreApproval ? 'pre-approval' : 'final-approval',
                'request_id' => $requestModel->unique_code,
            ]);
    
            $this->broadcastNewActivity($activity);
    
            // ✅ Check if all pre-approval managers have approved
            $allApproved = true;
            foreach ([1, 2, 3, 4] as $preManager) {
                $statusColumn = $managerToStatusMapping[$preManager];
                if ($requestModel->$statusColumn !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }
    
            // ✅ Proceed to the next process or move to finalrequests
            if ($allApproved) {
                Log::info("All pre-approval managers approved for request {$requestModel->unique_code}.");
                
                 // Explicitly set the timestamps
                 $finalRequestData['created_at'] = $requestModel->created_at;
                 $finalRequestData['updated_at'] = now(); // Current time for update
                 
                // Get the next process
                $nextProcess = DB::table('part_processes')
                    ->where('part_number', $requestModel->part_number)
                    ->where('process_order', '>', $requestModel->current_process_index)
                    ->orderBy('process_order')
                    ->first();
    
                if ($nextProcess) {
                    // ✅ Move to the next process
                    $requestModel->update([
                        'current_process_index' => $nextProcess->process_order,
                        'process_type' => $nextProcess->process_type,
                    ]);
    
                    // Reset pre-approval manager statuses to "pending"
                    foreach ($managerToStatusMapping as $statusCol) {
                        $requestModel->$statusCol = 'pending';
                    }
    
                    $requestModel->save();
                    $this->broadcastStatusUpdate($requestModel);
    
                    DB::commit();
    
                    return redirect()->route('manager.request-list')
                        ->with('success', "All pre-approval managers approved. Request proceeded to the next process: {$nextProcess->process_type}.");
                } else {
                    // ✅ No more processes → Move to finalrequests
                    // ✅ No more processes → Move to finalrequests
$finalRequestData = $requestModel->toArray();
unset($finalRequestData['id']); // Remove old ID before inserting into finalrequests

                    // 🚩 Set all manager statuses to "pending" when moving to `finalrequests`
                    $finalRequestData['manager_1_status'] = 'pending';
                    $finalRequestData['manager_2_status'] = 'pending';
                    $finalRequestData['manager_3_status'] = 'pending';
                    $finalRequestData['manager_4_status'] = 'pending';
                    $finalRequestData['manager_5_status'] = 'pending';
                    $finalRequestData['manager_6_status'] = 'pending';
    
                   // ✅ Set timestamps with proper formatting
    $finalRequestData['created_at'] = $requestModel->created_at->format('Y-m-d H:i:s');
    $finalRequestData['updated_at'] = now()->format('Y-m-d H:i:s');

    // ✅ Filter only valid fields to match table schema
    $validFields = Schema::getColumnListing('finalrequests');
    $filteredData = array_intersect_key($finalRequestData, array_flip($validFields));

    // ✅ Insert into finalrequests
    DB::table('finalrequests')->insert($filteredData);


                    // Delete from the original request table
                    $requestModel->delete();
    
                    DB::commit();
    
                    return redirect()->route('manager.request-list')
                        ->with('success', "All processes completed. Request moved to final approval.");
                }
            }
    
            // Broadcast status update if not fully approved yet
            $this->broadcastStatusUpdate($requestModel);
    
            DB::commit();
            return redirect()->back()->with('success', 'Request approved successfully.');
    
        } catch (\Exception $e) {
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
    try {
        $pusher = $this->initializePusher();


        // Broadcast the complete status update
        $pusher->trigger('requests-channel', 'status-updated', [
            'request' => [
                'unique_code' => $request->unique_code,
                'part_number' => $request->part_number,
                'process_type' => $request->process_type,
                'current_process_index' => $request->current_process_index,
                'total_processes' => $request->total_processes,
                'manager_1_status' => $request->manager_1_status ?? 'pending',
                'manager_2_status' => $request->manager_2_status ?? 'pending',
                'manager_3_status' => $request->manager_3_status ?? 'pending',
                'manager_4_status' => $request->manager_4_status ?? 'pending',
                'status' => $request->status,
                'created_at' => $request->created_at->toDateTimeString(),
            ]
        ]);

        Log::info('Status update broadcasted for request: ' . $request->unique_code, [
            'process_index' => $request->current_process_index,
            'process_type' => $request->process_type
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to broadcast status update:', [
            'error' => $e->getMessage(),
            'request_id' => $request->unique_code ?? 'unknown'
        ]);
    }
}


private function broadcastNewActivity($activity)
{
    $pusher = $this->initializePusher();

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
  

    private function initializePusher()
    {
        return new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]
        );
    }
    
}