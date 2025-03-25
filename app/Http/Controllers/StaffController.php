<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest; // Alias to avoid conflict
use App\Models\RequestModel;
use App\Models\Part;
use App\Models\Request; // Assuming this is your Request model
use App\Models\ManagerApproval;
use App\Models\Manager;
use App\Models\FinalRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all part numbers and names as an array of objects
        $parts = Part::select('part_number', 'part_name')->get();

        // Fetch all requests with the required fields
        $requests = Request::select(
            'unique_code',
            'description',
            'manager_1_status',
            'manager_2_status',
            'manager_3_status',
            'manager_4_status'
        )->get();

        // Fetch paginated requests
        $requests = RequestModel::orderBy('created_at', 'desc')->paginate(10);
        // Adjust the number of items per page as needed

        // Pass both parts and requests to the view
        return view('staff.staff_main', compact('parts', 'requests'));
    }

    public function create()
    {
        // Fetch all parts as a collection of objects
        $parts = Part::select('part_number', 'part_name')->get();

        return view('staff.create', compact('parts'));
    }

    public function store(HttpRequest $request)
    {
        try {
            // Debugging: Check if the files are being received
            if ($request->hasFile('attachment')) {
                \Log::info('Attachment received:', [
                    'name' => $request->file('attachment')->getClientOriginalName(),
                    'size' => $request->file('attachment')->getSize(),
                ]);
            } else {
                \Log::info('No attachment file received.');
            }

            if ($request->hasFile('final_approval_attachment')) {
                \Log::info('Final Approval Attachment received:', [
                    'name' => $request->file('final_approval_attachment')->getClientOriginalName(),
                    'size' => $request->file('final_approval_attachment')->getSize(),
                ]);
            } else {
                \Log::info('No final approval attachment received.');
            }

            // ✅ Validate the request data
           // ✅ Validate the request data
$validatedData = $request->validate([
    'unique_code' => 'required|string|max:255',
    'part_number' => 'required|string|max:255',
    'part_name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',          // Include Excel formats
    'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480', // Include Excel formats
]);


            // ✅ Handle file uploads
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
                $validatedData['attachment'] = $attachmentPath;
            } else {
                $validatedData['attachment'] = null;
            }

            if ($request->hasFile('final_approval_attachment')) {
                $finalApprovalPath = $request->file('final_approval_attachment')->store('final_attachments', 'public');
                $validatedData['final_approval_attachment'] = $finalApprovalPath;
            } else {
                $validatedData['final_approval_attachment'] = null;
            }

            // ✅ Insert the request into the `requests` table
            $newRequest = RequestModel::create([
                'unique_code' => $validatedData['unique_code'],
                'part_number' => $validatedData['part_number'],
                'part_name' => $validatedData['part_name'],
                'attachment' => $validatedData['attachment'],
                'final_approval_attachment' => $validatedData['final_approval_attachment'],
                'manager_1_status' => 'pending',
                'manager_2_status' => 'pending',
                'manager_3_status' => 'pending',
                'manager_4_status' => 'pending',
                'current_process_index' => 0,
                'total_processes' => $totalProcesses, // No longer counting processes
            ]);

            \Log::info("Inserted request data:", $newRequest->toArray());

            // ✅ Notify managers with manager_number 1, 2, 3, and 4
            $managers = Manager::whereIn('manager_number', [1, 2, 3, 4])->get();
            foreach ($managers as $manager) {
                Notification::create([
                    'user_id' => $manager->id,
                    'type' => 'new_request',
                    'message' => 'New request created by staff: ' . $newRequest->unique_code,
                ]);

                event(new NewRequestNotification('New request created by staff: ' . $newRequest->unique_code, $manager->id));
            }

            return response()->json(['success' => 'Request submitted successfully!', 'request' => $newRequest]);

        } catch (\Exception $e) {
            \Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'An error occurred while submitting the request.'], 500);
        }
    }

    public function finalList()
    {
        // Fetch the latest final requests and paginate them
        $finalRequests = FinalRequest::orderByDesc('created_at')->paginate(10);

        return view('staff.finallist', compact('finalRequests'));
    }

    public function showFinalDetails($unique_code)
    {
        // Fetch the final request details by unique_code
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->first();
    
        // If the request is not found, return a 404 error
        if (!$finalRequest) {
            abort(404);
        }
    
        // Calculate the number of managers who have approved, rejected, or marked the request as pending
        $approvedManagers = [];
        $rejectedManagers = [];
        $pendingManagers = [];
    
        // Assuming there are 4 managers (manager_1_status, manager_2_status, etc.)
        for ($i = 1; $i <= 6; $i++) {
            $status = $finalRequest->{'manager_' . $i . '_status'};
            if ($status === 'approved') {
                $approvedManagers[] = 'Manager ' . $i;
            } elseif ($status === 'rejected') {
                $rejectedManagers[] = 'Manager ' . $i;
            } else {
                $pendingManagers[] = 'Manager ' . $i;
            }
        }
    
        // Pass the final request details and manager status counts to the view
        return view('staff.final_details', compact('finalRequest', 'approvedManagers', 'rejectedManagers', 'pendingManagers'));
    }

    public function showRequestDetails($unique_code)
    {
        // Fetch the request by unique_code
        $request = Request::where('unique_code', $unique_code)->firstOrFail();

        // Initialize manager status arrays
        $approvedManagers = [];
        $rejectedManagers = [];
        $pendingManagers = [];

        // Check each manager's status and categorize them
        if ($request->manager_1_status === 'approved') {
            $approvedManagers[] = 'Manager 1';
        } elseif ($request->manager_1_status === 'rejected') {
            $rejectedManagers[] = 'Manager 1';
        } else {
            $pendingManagers[] = 'Manager 1';
        }

        if ($request->manager_2_status === 'approved') {
            $approvedManagers[] = 'Manager 2';
        } elseif ($request->manager_2_status === 'rejected') {
            $rejectedManagers[] = 'Manager 2';
        } else {
            $pendingManagers[] = 'Manager 2';
        }

        if ($request->manager_3_status === 'approved') {
            $approvedManagers[] = 'Manager 3';
        } elseif ($request->manager_3_status === 'rejected') {
            $rejectedManagers[] = 'Manager 3';
        } else {
            $pendingManagers[] = 'Manager 3';
        }

        if ($request->manager_4_status === 'approved') {
            $approvedManagers[] = 'Manager 4';
        } elseif ($request->manager_4_status === 'rejected') {
            $rejectedManagers[] = 'Manager 4';
        } else {
            $pendingManagers[] = 'Manager 4';
        }

        // Pass the request and manager status arrays to the view
        return view('staff.request_details', compact('request', 'approvedManagers', 'rejectedManagers', 'pendingManagers'));
    }

    public function requestHistory()
    {
        $histories = DB::table('request_histories')
            ->select(
                'id',
                'unique_code',
                'part_number',
                'description',
                'status',
                'completed_at',
                'created_at'
            )
            ->where('staff_id', Auth::guard('staff')->id())
            ->orderBy('completed_at', 'desc')
            ->paginate(10);

        // ✅ Log the data instead of using dd() to avoid stopping execution
        Log::info('Fetched Histories:', $histories->toArray());

        // ✅ Return the view with data
        return view('staff.request_history', compact('histories'));
    }
    public function downloadAttachment($filename)
    {
        try {
            $path = 'attachments/' . $filename;
            
            if (!Storage::disk('public')->exists($path)) {
                abort(404);
            }

            return Storage::disk('public')->download($path);
        } catch (\Exception $e) {
            Log::error('Attachment download failed: ' . $e->getMessage());
            abort(500, 'Failed to download attachment');
        }
    }

    /**
     * Download final approval attachment
     */
    public function downloadFinalAttachment($filename)
    {
        try {
            $path = 'final_attachments/' . $filename;
            
            if (!Storage::disk('public')->exists($path)) {
                abort(404);
            }

            return Storage::disk('public')->download($path);
        } catch (\Exception $e) {
            Log::error('Final attachment download failed: ' . $e->getMessage());
            abort(500, 'Failed to download final attachment');
        }
    }
    public function update(HttpRequest $request, $id)
    {
        try {
            $requestModel = RequestModel::findOrFail($id);

            $validatedData = $request->validate([
                'description' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
            ]);

            // Handle attachment update
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($requestModel->attachment) {
                    Storage::disk('public')->delete($requestModel->attachment);
                }
                
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
                $requestModel->attachment = $attachmentPath;
            } elseif ($request->has('remove_attachment')) {
                // Remove attachment if requested
                if ($requestModel->attachment) {
                    Storage::disk('public')->delete($requestModel->attachment);
                }
                $requestModel->attachment = null;
            }

            // Update other fields
            $requestModel->description = $validatedData['description'] ?? $requestModel->description;
            $requestModel->save();

            return response()->json(['success' => 'Request updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Error updating request: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update request'], 500);
        }
    }
}

