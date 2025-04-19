<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;  // Correct import for HTTP request
use Illuminate\Routing\Controller;
use App\Models\Staff;
use Illuminate\Http\Request; 
use App\Models\Manager;
use App\Models\Part;
use App\Models\PartProcess;
use App\Models\RequestHistory; // Import the RequestHistory model
use App\Models\Request as RequestModel;  // Alias the Request model to avoid conflicts with the HTTP Request
use App\Models\FinalRequest as FinalRequestModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Add this line


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

    // ✅ Staff Table (Alternate method for display)
    public function staffTable()
{
    $staff = Staff::paginate(10);
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
public function managerTable()
{
    $managers = Manager::paginate(10); // 10 items per page
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
    public function partsTable()
    {
        $parts = Part::paginate(10)->onEachSide(1); // Show 3 pagination numbers: current ±1
        return view('superadmin.parts_table', compact('parts'));
    }
    public function destroyPart($id)
{
    $part = Part::findOrFail($id);
    $part->delete();

    return response()->json([
        'success' => true,
        'message' => 'Part deleted successfully'
    ]);
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

    return response()->json([
        'success' => true,
        'message' => 'Part updated successfully'
    ]);
}




public function partProcessTable()
{
    $partProcesses = PartProcess::paginate(10);
    return view('superadmin.partprocess_table', compact('partProcesses'));
}
public function destroyPartProcess($id)
{
    $partProcess = PartProcess::findOrFail($id);
    $partProcess->delete();

    return response()->json([
        'success' => true,
        'message' => 'Part Process deleted successfully'
    ]);
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
        'process_order' => 'required|integer',
    ]);

    $partProcess = PartProcess::findOrFail($id);
    $partProcess->update($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Part Process updated successfully'
    ]);
}




// ✅ Request Table (Paginated method with date filtering)
public function requestTable(Request $request)
{
    $query = RequestModel::query();
    // Get paginated results (10 per page) ordered by newest first
    $requests = $query->orderBy('created_at', 'desc')->paginate(10);
    
    // Append all query parameters to pagination links
    $requests->appends(request()->query());
    
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
public function finalRequestTable()
{
    $finalRequests = FinalRequestModel::paginate(10); // 10 items per page
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
public function requestHistoryTable()
{
    $requestHistories = RequestHistory::paginate(10); // You can adjust the number per page
    return view('superadmin.requesthistory_table', compact('requestHistories'));
}
// Destroy Request History
public function destroyRequestHistory($id)
{
    $requestHistory = RequestHistory::findOrFail($id);  // Use the aliased RequestHistoryModel
    $requestHistory->delete();

    return response()->json([
        'success' => true,
        'message' => 'Request history deleted successfully'
    ]);
}
// Edit Request History Form
public function editRequestHistory($id)
{
    $requestHistory = RequestHistory::findOrFail($id);  // Use the aliased RequestHistoryModel
    return view('superadmin.request_history_edit', compact('requestHistory'));
}
// Update Request History
public function updateRequestHistory(HttpRequest $httpRequest, $id)  // Renamed the parameter to $httpRequest
{
    $httpRequest->validate([
        'staff_id' => 'required|string|max:255',  // Example validation rules
        'unique_code' => 'required|string|max:255',
        'part_number' => 'required|string|max:255',
        'part_name' => 'required|string|max:255',
        'created_at' => 'required|date',  // Example field validation
    ]);

    $requestHistoryToUpdate = RequestHistory::findOrFail($id);  // Use the aliased RequestHistoryModel
    $requestHistoryToUpdate->update($httpRequest->all());  // Use the renamed variable

    return response()->json([
        'success' => true,
        'message' => 'Request history updated successfully'
    ]);
}




    // ✅ Logout
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin.login');  // Change this to your login route
    }

}
