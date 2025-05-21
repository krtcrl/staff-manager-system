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
use App\Models\Manager; // Add this line
use App\Notifications\ApprovalNotification; // Add this line
use Illuminate\Support\Carbon;
use App\Models\RequestLog;
use App\Notifications\RejectNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    
    public function index()
    {
        Log::info('Pusher credentials from .env:', [
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'cluster' => env('PUSHER_APP_CLUSTER'),
        ]);
    
        $managerNumber = Auth::guard('manager')->user()->manager_number;
    
        // Mapping of manager numbers to status columns
        $statusMapping = [
            1 => ['pre' => 'manager_1_status', 'final' => 'manager_1_status'],
            2 => ['pre' => 'manager_2_status'],
            3 => ['pre' => 'manager_3_status'],
            4 => ['pre' => 'manager_4_status'],
            5 => ['final' => 'manager_2_status'],
            6 => ['final' => 'manager_3_status'],
            7 => ['final' => 'manager_4_status'],
            8 => ['final' => 'manager_5_status'],
            9 => ['final' => 'manager_6_status'],
        ];
    
        $pendingRequests = 0;
        $pendingFinalRequests = 0;
        $requests = collect();
    
        $statusConfig = $statusMapping[$managerNumber] ?? [];
    
        // Handle pre-approval requests
        if (isset($statusConfig['pre'])) {
            $preStatusCol = $statusConfig['pre'];
            $preRequests = RequestModel::where($preStatusCol, 'pending')->get();
            $pendingRequests = $preRequests->count();
        }
    
        // Handle final approval requests
        if (isset($statusConfig['final'])) {
            $finalStatusCol = $statusConfig['final'];
            $finalRequests = FinalRequest::where($finalStatusCol, 'pending')->get();
            $pendingFinalRequests = $finalRequests->count();
        }
    
        // Count new requests today
        $newRequestsToday = DB::table('request_logs')
            ->where('action', 'created')
            ->whereDate('created_at', today())
            ->count();
    
        // Fetch recent activities for this manager
        $recentActivities = Activity::where('manager_id', Auth::guard('manager')->id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    
        return view('manager.manager_main', compact(
            'requests',
            'newRequestsToday',
            'pendingRequests',
            'pendingFinalRequests',
            'recentActivities'
        ));
    }
    
// Show the password change form
public function showChangePasswordForm()
{
    return view('manager.change-password');
}

 // Handle password change with Validator facade
 public function changePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $manager = Auth::guard('manager')->user();

    if (!Hash::check($request->current_password, $manager->password)) {
        return back()->with('error', 'Current password is incorrect');
    }

    $manager->password = Hash::make($request->new_password);
    $manager->save();

    return back()->with('success', 'Password changed successfully');
}


    public function dashboard()
    {
        $managerNumber = Auth::guard('manager')->user()->manager_number;
        
        // New requests created today
        $newRequestsToday = DB::table('request_logs')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        // Calculate percentage change from yesterday
        $yesterdayCount = DB::table('request_logs')
            ->whereDate('created_at', now()->subDay()->toDateString())
            ->count();
        $newRequestsChange = $yesterdayCount > 0 
            ? round((($newRequestsToday - $yesterdayCount) / $yesterdayCount) * 100)
            : 0;
    
        // Pending pre-approvals
        $pendingRequests = 0;
        if (in_array($managerNumber, [1, 2, 3, 4])) {
            $statusColumn = "manager_{$managerNumber}_status";
            $pendingRequests = DB::table('request_logs')
                ->where($statusColumn, 'pending')
                ->count();
        }
    
        // Pending final approvals
        $pendingFinalRequests = 0;
        if (in_array($managerNumber, [1, 5, 6, 7, 8, 9])) {
            $finalColumns = [
                1 => 'manager_1_status',
                5 => 'manager_2_status',
                6 => 'manager_3_status',
                7 => 'manager_4_status',
                8 => 'manager_5_status',
                9 => 'manager_6_status',
            ];
    
            $statusColumn = $finalColumns[$managerNumber] ?? null;
    
            if ($statusColumn) {
                $pendingFinalRequests = DB::table('request_logs')
                    ->where($statusColumn, 'pending')
                    ->count();
            }
        }
    
        // Calculate approval metrics in a single query
        $approvalMetrics = DB::table('request_logs')
            ->select([
                DB::raw('COUNT(CASE WHEN final_status = "approved" THEN 1 END) as approved_count'),
                DB::raw('COUNT(CASE WHEN final_status IS NOT NULL THEN 1 END) as total_processed'),
                DB::raw('AVG(TIMESTAMPDIFF(DAY, created_at, COALESCE(approved_at, NOW()))) as avg_processing_time'),
                DB::raw('COUNT(CASE WHEN status = "pending" AND created_at <= ? THEN 1 END) as overdue_count')
            ], [now()->subDays(3)])
            ->first();
    
        $approvalRate = $approvalMetrics->total_processed > 0 
            ? round(($approvalMetrics->approved_count / $approvalMetrics->total_processed) * 100)
            : 0;
        
        // Calculate average processing time (in days)
        $avgProcessingTime = $approvalMetrics->avg_processing_time 
            ? round($approvalMetrics->avg_processing_time, 1)
            : 0;
    
        // Urgent/overdue tasks
        $urgentTasks = $approvalMetrics->overdue_count;
        $overdueTasks = $urgentTasks; // Can differentiate these if needed
    
        // Recent activities (last 5 days)
        $recentActivities = DB::table('request_logs')
            ->where('created_at', '>=', now()->subDays(5))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    
        // Process timeline
        $currentStage = in_array($managerNumber, [1, 2, 3, 4]) ? 'Pre-Approval' : 'Final Approval';
        $currentAction = in_array($managerNumber, [1, 2, 3, 4]) 
            ? 'Review pre-approval requirements' 
            : 'Verify all documentation';
    
        $processTimeline = [
            [
                'stage' => 'Submission',
                'description' => 'Request submitted by client',
                'status' => 'completed',
                'date' => now()->subDays(4)->format('M j'),
                'action' => ''
            ],
            [
                'stage' => 'Initial Review',
                'description' => 'Basic documentation check',
                'status' => 'completed',
                'date' => now()->subDays(3)->format('M j'),
                'action' => ''
            ],
            [
                'stage' => $currentStage,
                'description' => "Waiting for your $currentStage",
                'status' => 'current',
                'date' => 'Today',
                'action' => $currentAction
            ],
            [
                'stage' => 'Completion',
                'description' => 'Final processing',
                'status' => 'pending',
                'date' => '',
                'action' => ''
            ]
        ];
    
        // Team members - example data (replace with actual query)
        $teamMembers = [
            ['name' => 'John Doe', 'role' => 'Pre-Approval Manager', 'status' => 'Available'],
            ['name' => 'Jane Smith', 'role' => 'Final Approval Manager', 'status' => 'Busy'],
            ['name' => 'Mike Johnson', 'role' => 'Support Staff', 'status' => 'Available']
        ];
    
        // Team performance metrics (example values - replace with actual calculations)
        $teamScore = 8; // 1-10 scale
        $teamScoreChange = 2; // percentage change
        $approvalRateChange = 5; // percentage change
    
        return view('manager.manager_main', [
            'newRequestsToday' => $newRequestsToday,
            'newRequestsChange' => $newRequestsChange,
            'pendingRequests' => $pendingRequests,
            'pendingFinalRequests' => $pendingFinalRequests,
            'recentActivities' => $recentActivities,
            'approvalRate' => $approvalRate,
            'approvalRateChange' => $approvalRateChange,
            'urgentTasks' => $urgentTasks,
            'overdueTasks' => $overdueTasks,
            'teamScore' => $teamScore,
            'teamScoreChange' => $teamScoreChange,
            'avgProcessingTime' => $avgProcessingTime, // This is now properly calculated and passed
            'processTimeline' => $processTimeline,
            'teamMembers' => $teamMembers
        ]);
    }
    private function calculateRequestChange()
{
    $todayCount = Request::whereDate('created_at', today())->count();
    $yesterdayCount = Request::whereDate('created_at', today()->subDay())->count();
    
    if ($yesterdayCount == 0) return 0;
    
    return round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100);
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
        $preApprovalStatusMapping = [
            1 => 'manager_1_status',
            2 => 'manager_2_status',
            3 => 'manager_3_status',
            4 => 'manager_4_status',
        ];

        // Final approval manager numbers and their status columns
        $finalApprovalManagers = [
            1 => 'manager_1_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status',
        ];

        // Identify if pre-approval or final approval
        $isPreApproval = in_array($managerNumber, [1, 2, 3, 4]);
        $requestModel = $isPreApproval 
            ? RequestModel::where('unique_code', $unique_code)->firstOrFail()
            : FinalRequest::where('unique_code', $unique_code)->firstOrFail();

        // Update the current manager's status to approved
        $statusColumn = $isPreApproval 
            ? $preApprovalStatusMapping[$managerNumber]
            : $finalApprovalManagers[$managerNumber];
        $requestModel->$statusColumn = 'approved';
        $requestModel->save();

        // Check if all required managers have approved (AFTER updating current manager)
        if ($isPreApproval) {
            // For pre-approval: check all 4 managers
            $allApproved = true;
            foreach ([1, 2, 3, 4] as $preManager) {
                $statusColumn = $preApprovalStatusMapping[$preManager];
                if ($requestModel->$statusColumn !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }
        } else {
            // For final approval: check all final managers
            $allApproved = true;
            foreach (array_keys($finalApprovalManagers) as $finalManager) {
                $statusColumn = $finalApprovalManagers[$finalManager];
                if ($requestModel->$statusColumn !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }
        }

        // Only notify staff about individual approval if not all have approved
        if (!$allApproved) {
            $staff = $requestModel->staff_id ? \App\Models\Staff::find($requestModel->staff_id) : null;
            if ($staff) {
                $staffData = [
                    'title' => 'Request Approved',
                    'message' => "Your request {$requestModel->unique_code} has been approved by Manager {$managerNumber}",
                    'url' => $isPreApproval 
                        ? route('staff.request.details', $requestModel->unique_code)
                        : route('staff.final.details', $requestModel->unique_code),
                    'type' => 'approval',
                    'request_id' => $requestModel->unique_code,
                    'manager_number' => $managerNumber,
                ];
                $staff->notify(new \App\Notifications\StaffNotification($staffData));
            }
        }

        // Log activity
        $activity = Activity::create([
            'manager_id' => $manager->id,
            'type' => 'approval',
            'description' => "Request {$requestModel->unique_code} approved by Manager $managerNumber.",
            'request_type' => $isPreApproval ? 'pre-approval' : 'final-approval',
            'request_id' => $requestModel->unique_code,
        ]);

        $this->broadcastNewActivity($activity);

        // Handle case where all required managers have approved
        if ($allApproved) {
            if ($isPreApproval) {
                // Handle pre-approval completion
                Log::info("All pre-approval managers approved for request {$requestModel->unique_code}.");

                // Get the current process
                $currentProcess = DB::table('request_processes')
                    ->where('unique_code', $requestModel->unique_code)
                    ->where('process_order', $requestModel->current_process_index)
                    ->first();

                // Get the next process
                $nextProcess = DB::table('request_processes')
                    ->where('unique_code', $requestModel->unique_code)
                    ->where('process_order', '>', $requestModel->current_process_index)
                    ->orderBy('process_order')
                    ->first();

                // Get total processes count
                $totalProcesses = DB::table('request_processes')
                    ->where('unique_code', $requestModel->unique_code)
                    ->count();

                // Check if this is the last process
                $isLastProcess = !$nextProcess || ($requestModel->current_process_index >= $totalProcesses);

                // If this is the last process, move to final approval
                if ($isLastProcess) {
                    Log::info("Last process completed for request {$requestModel->unique_code}. Moving to final approval.");

                    try {
                        // First delete the related request_processes records
                        DB::table('request_processes')
                            ->where('unique_code', $requestModel->unique_code)
                            ->delete();

                        // Move to finalrequests
                        $finalRequestData = $requestModel->toArray();
                        unset($finalRequestData['id']);

                        // Reset all final approval manager statuses
                        foreach ($finalApprovalManagers as $statusCol) {
                            $finalRequestData[$statusCol] = 'pending';
                        }

                        // Set timestamps
                        $finalRequestData['created_at'] = $requestModel->created_at->format('Y-m-d H:i:s');
                        $finalRequestData['updated_at'] = now()->format('Y-m-d H:i:s');

                        // Filter valid fields
                        $validFields = Schema::getColumnListing('finalrequests');
                        $filteredData = array_intersect_key($finalRequestData, array_flip($validFields));

                        // Insert into finalrequests
                        $finalRequest = FinalRequest::create($filteredData);

                        // Now delete the original request
                        $requestModel->delete();

                        // Notify final approval managers
                        $finalManagers = \App\Models\Manager::whereIn('manager_number', array_keys($finalApprovalManagers))->get();
                        $url = route('manager.finalrequest.details', $unique_code);

                        foreach ($finalManagers as $mgr) {
                            $mgr->notify(new ApprovalNotification(
                                $finalRequest,
                                $url,
                                'Ready for final approval'
                            ));
                            Log::info("Notified manager {$mgr->manager_number} about final approval for request {$unique_code}");
                        }

                        // Notify staff about moving to final
                        $staff = $finalRequest->staff_id ? \App\Models\Staff::find($finalRequest->staff_id) : null;
                        if ($staff) {
                            $staffData = [
                                'title' => 'Final Approval Stage',
                                'message' => "Your request {$finalRequest->unique_code} has moved to final approval",
                                'url' => route('staff.final.details', $finalRequest->unique_code),
                                'type' => 'final_approval',
                                'request_id' => $finalRequest->unique_code,
                                'manager_number' => $managerNumber,
                            ];
                            $staff->notify(new \App\Notifications\StaffNotification($staffData));
                        }

                        DB::commit();
                        return redirect()->route('manager.request-list')
                            ->with('success', "All processes completed. Request moved to final approval.");
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error moving to final approval:', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'request' => $requestModel->unique_code
                        ]);
                        throw $e;
                    }
                } else {
                    // Move to the next process
                    $requestModel->update([
                        'current_process_index' => $nextProcess->process_order,
                        'process_type' => $nextProcess->process_type,
                    ]);

                    // Reset pre-approval manager statuses to "pending"
                    foreach ($preApprovalStatusMapping as $statusCol) {
                        $requestModel->$statusCol = 'pending';
                    }

                    $requestModel->save();
                    $this->broadcastStatusUpdate($requestModel);

                    // Notify only manager 1 when moving to next process
                    $nextManager = \App\Models\Manager::where('manager_number', 1)->first();
                    $url = route('manager.request.details', $requestModel->unique_code);

                    if ($nextManager) {
                        $nextManager->notify(new ApprovalNotification(
                            $requestModel,
                            $url,
                            'New process started - Your approval required'
                        ));
                    }

                    // Notify staff about moving to next process
                    $staff = $requestModel->staff_id ? \App\Models\Staff::find($requestModel->staff_id) : null;
                    if ($staff) {
                        $staffData = [
                            'title' => 'Request Progress Update',
                            'message' => "Your request {$requestModel->unique_code} has moved to the next process: {$nextProcess->process_type}",
                            'url' => route('staff.request.details', $requestModel->unique_code),
                            'type' => 'progress',
                            'request_id' => $requestModel->unique_code,
                            'manager_number' => $managerNumber,
                        ];
                        $staff->notify(new \App\Notifications\StaffNotification($staffData));
                    }

                    DB::commit();
                    return redirect()->route('manager.request-list')
                        ->with('success', "All managers approved. Request proceeded to the next process: {$nextProcess->process_type}.");
                }
            } else {
                // Handle final approval completion
                Log::info("All final approval managers approved for request {$requestModel->unique_code}.");
                
                // Mark request as fully approved
                $requestModel->update(['status' => 'fully_approved']);
                
                // Notify staff
                $staff = $requestModel->staff_id ? \App\Models\Staff::find($requestModel->staff_id) : null;
                if ($staff) {
                    $staffData = [
                        'title' => 'Request Fully Approved',
                        'message' => "Your request {$requestModel->unique_code} has been fully approved",
                        'url' => route('staff.final.details', $requestModel->unique_code),
                        'type' => 'final_approval',
                        'request_id' => $requestModel->unique_code,
                    ];
                    $staff->notify(new \App\Notifications\StaffNotification($staffData));
                }

                DB::commit();
                return redirect()->route('manager.request-list')
                    ->with('success', "Request fully approved and completed.");
            }
        }

        // If not all have approved, notify next manager
        if ($isPreApproval) {
            // For pre-approval: notify next manager in sequence 1-4
            $nextManagerNumber = $managerNumber + 1;
            if ($nextManagerNumber > 4) {
                $nextManagerNumber = 1;
            }
            $statusCol = $preApprovalStatusMapping[$nextManagerNumber];
        } else {
            // For final approval: notify next manager in sequence 1,5,6,7,8,9
            $finalManagerNumbers = array_keys($finalApprovalManagers);
            $currentIndex = array_search($managerNumber, $finalManagerNumbers);
            $nextIndex = ($currentIndex + 1) % count($finalManagerNumbers);
            $nextManagerNumber = $finalManagerNumbers[$nextIndex];
            $statusCol = $finalApprovalManagers[$nextManagerNumber];
        }

        // Verify the next manager hasn't already approved
        if ($requestModel->$statusCol === 'pending') {
            $nextManager = \App\Models\Manager::where('manager_number', $nextManagerNumber)->first();

            if ($nextManager) {
                $url = $isPreApproval 
                    ? route('manager.request.details', $requestModel->unique_code)
                    : route('manager.finalrequest.details', $requestModel->unique_code);
                    
                $nextManager->notify(new ApprovalNotification(
                    $requestModel,
                    $url,
                    $nextManagerNumber
                ));

                Log::info("Notified next manager {$nextManagerNumber} about pending approval for request {$requestModel->unique_code}");
            }
        }

        $this->broadcastStatusUpdate($requestModel);
        DB::commit();
        return redirect()->back()->with('success', 'Request approved successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in approval process:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request' => $unique_code,
            'manager' => $managerNumber ?? 'unknown'
        ]);

        return redirect()->back()->with('error', 'An error occurred while approving. No changes were made. Error: '.$e->getMessage());
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
    
            // Notify the staff about the rejection using RejectNotification
            if ($requestModel->staff) {
                $staffData = [
                    'request_id' => $requestModel->unique_code,
                    'manager_number' => $managerNumber,
                    'url' => route('staff.request.details', $requestModel->unique_code),
                    'type' => 'rejected',
                    'message' => "Your request {$requestModel->unique_code} has been rejected by Manager {$managerNumber}. Reason: $rejectionReason",
                    'rejection_reason' => $rejectionReason
                ];
                // Send both database and email notifications
                $requestModel->staff->notify(new \App\Notifications\RejectNotification($requestModel, $staffData['url'], $managerNumber, $rejectionReason));
            }
    
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
    $managerNumber = auth()->user()->manager_number ?? 1; // Fetch the current manager number
    
    // Paginated request list
    $requests = RequestModel::orderBy('created_at', 'desc')->paginate(10);
    
    // Total count
    $totalRequests = RequestModel::count();
    
    // Manager-specific counts based on the current manager number
    $approvedRequests = RequestModel::where("manager_{$managerNumber}_status", 'approved')->count();
    $pendingRequests = RequestModel::where("manager_{$managerNumber}_status", 'pending')->count();
    $rejectedRequests = RequestModel::where("manager_{$managerNumber}_status", 'rejected')->count();
    
    return view('manager.request_list', compact(
        'requests', 
        'totalRequests', 
        'approvedRequests', 
        'pendingRequests', 
        'rejectedRequests', 
        'managerNumber' // Pass managerNumber to the view
    ));
}

    
    
    
    /**
     * Display the list of final requests.
     *
     * @return \Illuminate\View\View
     */
    public function finalRequestList()
    {
        $manager = Auth::guard('manager')->user();
        $managerNumber = $manager->manager_number;
    
        // Define the mapping of manager numbers to status columns
        $managerColumnMap = [
            1 => 'manager_1_status',
            5 => 'manager_2_status',
            6 => 'manager_3_status',
            7 => 'manager_4_status',
            8 => 'manager_5_status',
            9 => 'manager_6_status'
        ];
    
        // Get the correct column name for this manager
        $statusColumn = $managerColumnMap[$managerNumber] ?? null;
    
        if (!$statusColumn) {
            abort(403, 'Unauthorized access');
        }
    
        // Fetch all final requests sorted by creation date
        $finalRequests = FinalRequest::orderBy('created_at', 'desc')->paginate(10);
    
        // Counts for the dashboard stats
        $totalRequests = FinalRequest::count();
        $approvedRequests = FinalRequest::where($statusColumn, 'approved')->count();
        $pendingRequests = FinalRequest::where($statusColumn, 'pending')->count();
        $rejectedRequests = FinalRequest::where($statusColumn, 'rejected')->count();
    
        // Pass all the necessary data to the view
        return view('manager.finalrequest_list', compact(
            'finalRequests', 
            'totalRequests', 
            'approvedRequests',
            'pendingRequests',
            'rejectedRequests',
            'statusColumn'
        ));
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