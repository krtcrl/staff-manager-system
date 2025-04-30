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
use Illuminate\Support\Facades\Hash;
use App\Models\FinalRequest as FinalRequestModel;
use App\Models\Staff;   
use App\Models\PartProcess;
use Illuminate\Support\Facades\Validator;


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
// Show the password change form
public function showChangePasswordForm()
{
    return view('staff.change-password');
}

 // Handle password change with Validator facade
 public function changePassword(HttpRequest $request)
{
    $validated = $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $staff = Auth::guard('staff')->user();

    if (!Hash::check($request->current_password, $staff->password)) {
        return back()->with('error', 'Current password is incorrect');
    }

    $staff->password = Hash::make($request->new_password);
    $staff->save();

    return back()->with('success', 'Password changed successfully');
}



public function index()
{
    // Fetch all part numbers and names as an array of objects
    $parts = Part::select('part_number', 'part_name')->get();

    // Request statistics
    $requestsCount = RequestModel::count();
    $finalRequestsCount = FinalRequestModel::count(); // Update this model if needed
    $requestHistoriesCount = RequestHistory::count(); // Update this model if needed

    // Other statistics
    $staffCount = Staff::count();
    $managersCount = Manager::count();

    // Monthly Request Counts
    $monthlyRequestCounts = DB::table('requests') // This is fine if you have a 'requests' table
        ->select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as count"))
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->pluck('count', 'month')
        ->toArray();

    // Monthly Final Request Counts
    $monthlyFinalRequestCounts = DB::table('finalrequests') // Corrected table name
        ->select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as count"))
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->pluck('count', 'month')
        ->toArray();

    // Monthly Request History Counts
    $monthlyRequestHistoryCounts = DB::table('request_histories') // Corrected table name
        ->select(DB::raw("MONTH(created_at) as month"), DB::raw("COUNT(*) as count"))
        ->groupBy(DB::raw("MONTH(created_at)"))
        ->pluck('count', 'month')
        ->toArray();

    // Fill in 0 for months with no data
    $formattedMonthlyCounts = [];
    for ($i = 1; $i <= 12; $i++) {
        $formattedMonthlyCounts[] = $monthlyRequestCounts[$i] ?? 0;
    }

    $formattedMonthlyFinalCounts = [];
    for ($i = 1; $i <= 12; $i++) {
        $formattedMonthlyFinalCounts[] = $monthlyFinalRequestCounts[$i] ?? 0;
    }

    $formattedMonthlyHistoryCounts = [];
    for ($i = 1; $i <= 12; $i++) {
        $formattedMonthlyHistoryCounts[] = $monthlyRequestHistoryCounts[$i] ?? 0;
    }

    // Returning the view with all statistics
    return view('staff.page', compact(
        'requestsCount',
        'finalRequestsCount',
        'requestHistoriesCount',
        'staffCount',
        'managersCount',
        'formattedMonthlyCounts', // Pass to Blade
        'formattedMonthlyFinalCounts', // Pass to Blade
        'formattedMonthlyHistoryCounts', // Pass to Blade
        'parts'
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
         // Fetch all part numbers and names as an array of objects
    $parts = Part::select('part_number', 'part_name')->get();

        // Fetch the latest final requests and paginate them
        $finalRequests = FinalRequest::orderByDesc('created_at')->paginate(10);

        return view('staff.finallist', compact('finalRequests','parts'));
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
         // Fetch all part numbers and names as an array of objects
    $parts = Part::select('part_number', 'part_name')->get();

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
        return view('staff.request_history', compact('histories','parts'));
    }
    
   public function downloadAttachment($filename)
{
    try {
        // 1. Decode and sanitize the filename
        $cleanFilename = basename(urldecode($filename));
        
        // 2. Build the storage path
        $path = 'attachments/' . $cleanFilename;
        
        // 3. Verify the file exists
        if (!Storage::disk('public')->exists($path)) {
            Log::error("File not found in storage", [
                'requested_filename' => $filename,
                'clean_path' => $path,
                'storage_files' => Storage::disk('public')->files('attachments')
            ]);
            abort(404, 'File not found');
        }

        // 4. Get the full file path
        $fullPath = Storage::disk('public')->path($path);
        
        // 5. Return the file as a download response with proper headers
        return response()->download($fullPath, $cleanFilename, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => filesize($fullPath)
        ]);

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
   /* public function downloadFinalAttachment($filename)
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
    }*/
    
    
}

