<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest; // Alias to avoid conflict
use App\Models\RequestModel;
use App\Models\Part;
use App\Models\Request; // Assuming this is your Request model
use App\Models\ManagerApproval;
use App\Models\Notification;
use App\Models\Manager;
use App\Models\FinalRequest;
use App\Events\NewRequestNotification;
use Illuminate\Support\Facades\Log;


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
        $requests = RequestModel::paginate(10); // Adjust the number of items per page as needed

        
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
            // Debugging: Check if the file is being received
            if ($request->hasFile('attachment')) {
                \Log::info('File received:', [
                    'name' => $request->file('attachment')->getClientOriginalName(),
                    'size' => $request->file('attachment')->getSize(),
                ]);
            } else {
                \Log::info('No file received.');
            }
    
            // Validate the request data
            $validatedData = $request->validate([
                'unique_code' => 'required|unique:requests',
                'part_number' => 'required',
                'part_name' => 'required',
                'uph' => 'required|integer',
                'description' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:pdf|max:2048',
            ]);
    
            // ✅ Count total processes for the given part_number
            $totalProcesses = DB::table('part_processes')
                ->where('part_number', $validatedData['part_number'])
                ->count();
    
            \Log::info("Total processes for part number {$validatedData['part_number']}: $totalProcesses");
    
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('attachments', 'public');
                $validatedData['attachment'] = $attachmentPath;
            } else {
                $validatedData['attachment'] = null;
            }
    
            $newRequest = RequestModel::create([
                'unique_code' => $validatedData['unique_code'],
                'part_number' => $validatedData['part_number'],
                'part_name' => $validatedData['part_name'],
                'uph' => $validatedData['uph'],
                'description' => $validatedData['description'],
                'attachment' => $validatedData['attachment'],
                'manager_1_status' => 'pending',
                'manager_2_status' => 'pending',
                'manager_3_status' => 'pending',
                'manager_4_status' => 'pending',
                'current_process_index' => 0,
                'total_processes' => $totalProcesses,
            ]);
            
            \Log::info("Inserted request data:", $newRequest->toArray());
            
    
            // Notify managers with manager_number 1, 2, 3, and 4
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
        // Fetch final requests with pagination
        $finalRequests = FinalRequest::paginate(10);
    
        return view('staff.finallist', compact('finalRequests'));
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
}