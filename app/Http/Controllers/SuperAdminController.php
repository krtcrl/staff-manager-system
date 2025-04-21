<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;  // Correct import for HTTP request
use Illuminate\Routing\Controller;
use App\Models\Staff;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\Models\Manager;
use App\Models\Part;
use App\Models\PartProcess;
use App\Models\RequestHistory; // Import the RequestHistory model
use App\Models\Request as RequestModel;  // Alias the Request model to avoid conflicts with the HTTP Request
use App\Models\FinalRequest as FinalRequestModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Add this line
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Add this line
use App\Notifications\StaffAccountCreatedNotification;



class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:superadmin');
    }

    // ✅ Superadmin Dashboard as Index Page
public function dashboard()
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

    return view('superadmin.superadmin_main', compact(
        'requestsCount',
        'finalRequestsCount',
        'requestHistoriesCount',
        'staffCount',
        'managersCount',
        'partsCount',
        'partProcessesCount'
    ));
}

public function storeStaff(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:staff,email',
        'password' => 'required|string|min:8'
    ]);

    // Create the staff member
    $staff = Staff::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // Send notification to the staff email
    $staff->notify(new StaffAccountCreatedNotification($validatedData['password']));

    return response()->json([
        'success' => true,
        'message' => 'Staff account created successfully'
    ]);
}

public function storeManager(Request $request)
{
    $validatedData = $request->validate([
        'manager_number' => 'required|string|max:50|unique:users,manager_number',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8'
    ]);

    // Create the manager
    $manager = User::create([
        'manager_number' => $validatedData['manager_number'],
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // Send notification with the plain text password
    $manager->notify(new ManagerAccountCreatedNotification($validatedData['password']));

    return response()->json([
        'success' => true,
        'message' => 'Manager account created successfully. Notification sent.'
    ]);
}

public function storePart(Request $request)
{
    $validated = $request->validate([
        'part_number' => 'required|unique:parts',
        'part_name' => 'required'
    ]);

    Part::create($validated);

    return redirect()->route('superadmin.parts.table')
        ->with('success', 'Part created successfully');
}

public function storePartProcess(Request $request)
{
    $validated = $request->validate([
        'part_number' => 'required|exists:parts,part_number',
        'process_type' => 'required',
        'process_order' => 'required|integer|min:1'
    ]);

    PartProcess::create($validated);

    return redirect()->route('superadmin.partprocess.index')
        ->with('success', 'Process added successfully');
}


    // ✅ Staff Table (Alternate method for display)
    public function staffTable(Request $request)
    {
        $query = Staff::query();
    
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
    
        $staff = $query->orderBy('created_at', 'desc')->paginate(10);
    
        if ($request->has('search')) {
            $staff->appends(['search' => $request->input('search')]);
        }
    
        return view('superadmin.staff_table', compact('staff'));
    }
    

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:staff,email,' . $id,
    ]);

    $staff = Staff::findOrFail($id);
    $staff->update([
        'name' => $request->name,
        'email' => $request->email
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully'
        ]);
    }

    return redirect()->route('superadmin.staff.table')
        ->with('success', 'Staff member updated successfully');
}

public function destroy($id)
{
    $staff = Staff::findOrFail($id);
    $staff->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted successfully'
        ]);
    }

    return redirect()->route('superadmin.staff.table')
        ->with('success', 'Staff member deleted successfully');
}




// ✅ Manager Table (Paginated method)
public function managerTable(Request $request)
{
    $query = Manager::query();

    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%');
        });
    }

    $managers = $query->orderBy('created_at', 'desc')->paginate(10);

    if ($request->has('search')) {
        $managers->appends(['search' => $request->input('search')]);
    }

    return view('superadmin.manager_table', compact('managers'));
}

public function destroyManager($id)
{
    $manager = Manager::findOrFail($id);
    $manager->delete();

    return response()->json([
        'success' => true,
        'message' => 'Manager deleted successfully'
    ]);
}
// Edit Manager Form
public function editManager($id)
{
    $manager = Manager::findOrFail($id); // Find the manager by ID
    return view('superadmin.manager_edit', compact('manager'));
}
public function updateManager(Request $request, $id)
{
    $request->validate([
        'manager_number' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:managers,email,' . $id,
    ]);

    $manager = Manager::findOrFail($id);
    $manager->update([
        'manager_number' => $request->manager_number,
        'name' => $request->name,
        'email' => $request->email,
    ]);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Manager updated successfully'
        ]);
    }

    return redirect()->route('superadmin.manager.table')
        ->with('success', 'Manager updated successfully');
}





    // ✅ Staff Table (Alternate method for display)
    public function partsTable(Request $request)
    {
        $query = Part::query();
    
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('part_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('part_name', 'like', '%' . $searchTerm . '%');
            });
        }
    
        $parts = $query->orderBy('created_at', 'desc')->paginate(10)->onEachSide(1);
    
        if ($request->has('search')) {
            $parts->appends(['search' => $request->input('search')]);
        }
    
        return view('superadmin.parts_table', compact('parts'));
    }
    
    public function destroyPart($id)
{
    $part = Part::findOrFail($id);
    $part->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Part deleted successfully'
        ]);
    }

    return redirect()->route('superadmin.parts.table')
        ->with('success', 'Part deleted successfully');
}
// Edit Manager Form
public function editPart($id)
{
    $part = Part::findOrFail($id); // Find the manager by ID
    return view('superadmin.parts_edit', compact('part'));
}

public function updatePart(Request $request, $id)
{
    $request->validate([
        'part_number' => 'required|string|max:255',
        'part_name' => 'required|string|max:255',
    ]);

    $part = Part::findOrFail($id);
    $part->update($request->all());

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Part updated successfully'
        ]);
    }

    return redirect()->route('superadmin.parts.table')
        ->with('success', 'Part updated successfully');
}





public function partProcessTable(Request $request)
{
    $query = PartProcess::query();

    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('part_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('process_type', 'like', '%' . $searchTerm . '%');
        });
    }

    $partProcesses = $query->orderBy('created_at', 'desc')->paginate(10);

    if ($request->has('search')) {
        $partProcesses->appends(['search' => $request->input('search')]);
    }

    return view('superadmin.partprocess_table', compact('partProcesses'));
}


public function destroyPartProcess($id)
{
    $partProcess = PartProcess::findOrFail($id);
    $partProcess->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Process deleted successfully'
        ]);
    }

    return redirect()->route('superadmin.partprocess.table')
    ->with('success', 'Process deleted successfully');
}
// Edit Manager Form
public function editPartProcess($id)
{
    $partProcess = Part::findOrFail($id); // Find the manager by ID
    return view('superadmin.partprocess_edit', compact('partProcesses'));
}
public function updatePartProcess(Request $request, $id)
{        
       $request->validate([
            'part_number' => 'required|string|max:255',
            'process_type' => 'required|string|max:255',
            'process_order' => 'required|integer|min:1',
        ]);
        
        $process = PartProcess::findOrFail($id);
        $process->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Process updated successfully'
            ]);
        }
    
        return redirect()->route('superadmin.partprocess.table')
        ->with('success', 'Process updated successfully');
    }


// ✅ Request Table (Paginated method with date filtering)
public function requestTable(Request $request)
{
    $query = RequestModel::query();
    
    // Add search functionality
    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('part_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('part_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('unique_code', 'like', '%' . $searchTerm . '%');
        });
    }
    
    // Get paginated results (10 per page) ordered by newest first
    $requests = $query->orderBy('created_at', 'desc')->paginate(10);
    
    // Append all query parameters to pagination links
    if ($request->has('search')) {
        $requests->appends(['search' => $request->input('search')]);
    }
    
    return view('superadmin.request_table', compact('requests'));
}
public function destroyRequest($id)
{
    $requestModel = RequestModel::findOrFail($id);
    $requestModel->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Request deleted successfully'
        ]);
    }

    return redirect()->route('superadmin.request.table')
        ->with('success', 'Request deleted successfully');
}
public function updateRequest(Request $request, $id)
{
    $request->validate([
        'unique_code' => 'required|string|max:255',
        'part_number' => 'required|string|max:255',
        'part_name' => 'required|string|max:255',
    ]);

    $requestModel = RequestModel::findOrFail($id);
    $requestModel->update($request->all());

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Request updated successfully'
        ]);
    }

    return redirect()->route('superadmin.request.table')
        ->with('success', 'Request updated successfully');
}





// ✅ Final Request Table (Paginated method)
// Final Request Table (keep this as is)
public function finalRequestTable(Request $request)
{
    $query = FinalRequestModel::query();

    // Add search functionality
    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('part_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('part_name', 'like', '%' . $searchTerm . '%')
              ->orWhere('unique_code', 'like', '%' . $searchTerm . '%');
        });
    }

    // Paginate the results
    $finalRequests = $query->orderBy('created_at', 'desc')->paginate(10);

    // Preserve the search input in pagination links
    if ($request->has('search')) {
        $finalRequests->appends(['search' => $request->input('search')]);
    }

    return view('superadmin.finalrequest_table', compact('finalRequests'));
}

// Destroy Final Request (modified to work with your blade template)
public function destroyFinalRequest($id)
{
    $finalRequest = FinalRequestModel::findOrFail($id);
    
    // Delete the attachment file if exists
    if ($finalRequest->final_approval_attachment) {
        Storage::delete($finalRequest->final_approval_attachment);
    }
    
    $finalRequest->delete();

    return redirect()->route('superadmin.finalrequest.table')
        ->with('success', 'Final request deleted successfully');
}
// Update Final Request (modified to match your blade template fields)
public function updateFinalRequest(Request $request, $id)
{
    $validated = $request->validate([
        'unique_code' => 'required|string|max:255',
        'part_number' => 'required|string|max:255',
        'part_name' => 'required|string|max:255',
    ]);

    $finalRequest = FinalRequestModel::findOrFail($id);
    $finalRequest->update($request->all());

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Final Request updated successfully'
        ]);
    }

    return redirect()->route('superadmin.finalrequest.table')
        ->with('success', 'Final Request updated successfully');
}


// ✅ Request History Table (Alternate method for display)
public function requestHistoryTable(Request $request)
{
    $query = RequestHistory::query();

    if ($request->has('search')) {
        $searchTerm = $request->input('search');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('part_number', 'like', '%' . $searchTerm . '%')
              ->orWhere('part_name', 'like', '%' . $searchTerm . '%');
        });
    }

    $requestHistories = $query->orderBy('created_at', 'desc')->paginate(10);

    if ($request->has('search')) {
        $requestHistories->appends(['search' => $request->input('search')]);
    }

    return view('superadmin.requesthistory_table', compact('requestHistories'));
}

// Destroy Request History
public function destroyRequestHistory($id)
{
    $requestHistory = RequestHistory::findOrFail($id);  // Use the aliased RequestHistoryModel
    $requestHistory->delete();

    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Request history deleted successfully'
        ]);
    }

    return redirect()->route('superadmin.requesthistory.table')
        ->with('success', 'Request history deleted successfully');
}



    // ✅ Logout
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin.login');  // Change this to your login route
    }

}
