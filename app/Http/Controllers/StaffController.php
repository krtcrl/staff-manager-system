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

use App\Models\FinalRequest as FinalRequestModel;
use App\Models\Staff;   
use App\Models\PartProcess;

class StaffController extends Controller
{
    public function preList(Request $request)
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
    return view('staff.prelist', compact('parts', 'requests'));  // Changed to prelist view
}

    
    
    public function index()
    {
        // Request statistics
        $requestsCount = RequestModel::count();
        $finalRequestsCount = FinalRequestModel::count();
        $requestHistoriesCount = RequestHistory::count();
    
        // Other statistics
        $staffCount = Staff::count();
        $managersCount = Manager::count();
        $partsCount = Part::count();
        $partProcessesCount = PartProcess::count();
    
        // Returning the view with the statistics
        return view('staff.page', compact(
            'requestsCount',
            'finalRequestsCount',
            'requestHistoriesCount',
            'staffCount',
            'managersCount',
            'partsCount',
            'partProcessesCount'
        ));
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
        \Log::info("Accessing final details for: ".$unique_code);
        $finalRequest = FinalRequest::where('unique_code', $unique_code)->first();
        
        if (!$finalRequest) {
            \Log::error("FinalRequest not found for: ".$unique_code);
            abort(404, "Final request not found");
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
        // Fetch the request histories
        $histories = DB::table('request_histories')
            ->select(
                'id',
                'unique_code',
                'part_number',
                'part_name',
                'description',
                'staff_id',
                'status',
                'completed_at',
                'created_at',
            )
            ->orderBy('completed_at', 'desc')
            ->paginate(10);  // Paginate the results
        
      
        
        // Log the data
        Log::info('Fetched all histories:', $histories->toArray());
        
        // Return the view with data
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
    
    
}

