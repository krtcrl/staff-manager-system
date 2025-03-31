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
                'staff_id',
                'status',
                'completed_at',
                'created_at',
            )
            ->orderBy('completed_at', 'desc')
            ->paginate(10);  // ✅ Paginate the results
    
        // ✅ Log the data for debugging
        Log::info('Fetched all histories:', $histories->toArray());
    
        // ✅ Return the view with data
        return view('staff.request_history', compact('histories'));
    }
    public function downloadAttachment($filename)
{
    try {
        // 1. Decode the URL-encoded filename
        $decodedFilename = urldecode($filename);
        
        // 2. Sanitize the filename (security)
        $cleanFilename = basename($decodedFilename);
        
        // 3. Build the storage path
        $path = 'attachments/' . $cleanFilename;
        
        // 4. Verify the file exists
        if (!Storage::disk('public')->exists($path)) {
            Log::error("File not found in storage", [
                'requested_filename' => $filename,
                'clean_path' => $path,
                'storage_files' => Storage::disk('public')->files('attachments')
            ]);
            abort(404, 'File not found');
        }

        // 5. Force download with original filename
        return Storage::disk('public')->download($path, $cleanFilename);

    } catch (\Exception $e) {
        Log::error("Download failed", [
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
            // 1. Decode the URL-encoded filename
            $decodedFilename = urldecode($filename);
            
            // 2. Sanitize the filename (security)
            $cleanFilename = basename($decodedFilename);
    
            // 3. Build the correct storage path
            $path = 'final_approval_attachments/' . $cleanFilename;
    
            // 4. Verify the file exists
            if (!Storage::disk('public')->exists($path)) {
                Log::error("Final approval attachment not found", [
                    'requested_filename' => $filename,
                    'clean_path' => $path,
                    'storage_files' => Storage::disk('public')->files('final_approval_attachments')
                ]);
                abort(404, 'File not found');
            }
    
            // 5. Force download with original filename
            return Storage::disk('public')->download($path, $cleanFilename);
    
        } catch (\Exception $e) {
            Log::error("Final approval attachment download failed", [
                'error' => $e->getMessage(),
                'filename' => $filename ?? 'null'
            ]);
            abort(500, 'Download failed. Please try again.');
        }
    }
    
    public function update(HttpRequest $request, $id)
    {
        try {
            // Find the request by ID
            $requestModel = RequestModel::findOrFail($id);
    
            // ✅ Ensure `is_edited` is included in validation
            $validatedData = $request->validate([
                'unique_code'    => 'nullable|string',
                'part_number'    => 'nullable|string',
                'part_name'      => 'required|string|max:255',
                'description'    => 'nullable|string',
                'attachment'     => 'nullable|file|mimes:xls,xlsx,xlsb|max:20480',
                'is_edited'      => 'nullable|boolean'  // Ensure validation of is_edited
            ]);
    
            // Handle attachment update if a new file is uploaded
            if ($request->hasFile('attachment')) {
                $originalFileName = $request->file('attachment')->getClientOriginalName();
    
                // Delete old attachment if it exists
                if ($requestModel->attachment) {
                    Storage::disk('public')->delete('attachments/' . $requestModel->attachment);
                }
    
                // Store the new file
                $path = $request->file('attachment')->storeAs('attachments', $originalFileName, 'public');
                $requestModel->attachment = $originalFileName;
            }
    
            // Update fields
            $requestModel->unique_code = $validatedData['unique_code'] ?? $requestModel->unique_code;
            $requestModel->part_number = $validatedData['part_number'] ?? $requestModel->part_number;
            $requestModel->part_name = $validatedData['part_name'];
            $requestModel->description = $validatedData['description'] ?? $requestModel->description;
    
            // ✅ Reset only the rejected manager's status to pending
            for ($i = 1; $i <= 4; $i++) {
                $statusColumn = "manager_{$i}_status";
                
                if ($requestModel->$statusColumn === 'rejected') {
                    $requestModel->$statusColumn = 'pending';
                }
            }
    
            // ✅ Ensure `is_edited` is properly updated
            $requestModel->is_edited = true;
    
            // Save the request
            $saved = $requestModel->save();
    
            // ✅ Log the result for debugging
            Log::info('Updated Request', [
                'is_edited' => $requestModel->is_edited,
                'manager_statuses' => [
                    'm1' => $requestModel->manager_1_status,
                    'm2' => $requestModel->manager_2_status,
                    'm3' => $requestModel->manager_3_status,
                    'm4' => $requestModel->manager_4_status,
                ]
            ]);
    
            // Redirect with success message
            if ($saved) {
                return redirect()
                    ->route('staff.requests.show', $id)
                    ->with('success', 'Request updated successfully!');
            } else {
                return back()->with('error', 'Failed to update the request.');
            }
    
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    
}

