<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;  // Correct import for HTTP request
use Illuminate\Routing\Controller;
use App\Models\Staff;
use App\Models\Manager;
use App\Models\Part;
use App\Models\PartProcess;
use App\Models\RequestHistory; // Import the RequestHistory model
use App\Models\Request as RequestModel;  // Alias the Request model to avoid conflicts with the HTTP Request
use App\Models\FinalRequest as FinalRequestModel;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:superadmin');
    }

    // ✅ Dashboard View
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
    
    
    
    // ✅ Display All Staff
    public function index()
    {
        
        $staff = Staff::paginate(10); // 10 items per page
        return view('superadmin.staff_table', compact('staff'));
    }

    // ✅ Staff Table (Alternate method for display)
    public function staffTable()
    {
        $staff = Staff::all();
        return view('superadmin.staff_table', compact('staff'));
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted successfully'
        ]);
    }
    

    // ✅ Edit Staff Member Form
    public function edit($id)
    {
        $staff = Staff::findOrFail($id); // Find the staff member by ID
        return view('superadmin.staff_edit', compact('staff'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email,' . $id,
        ]);
    
        $staff = Staff::findOrFail($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully'
        ]);
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
        'email' => 'required|email|max:255|unique:managers,email,'.$id,
    ]);

    $manager = Manager::findOrFail($id);
    $manager->update($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Manager updated successfully'
    ]);
}


    // ✅ Staff Table (Alternate method for display)
    public function partsTable()
    {
        $parts = Part::all();
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
    $partProcesses = PartProcess::all();
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


// ✅ Request Table (Paginated method)
public function requestTable()
{
    $requests = RequestModel::paginate(10);  // Use pagination (10 items per page)
    return view('superadmin.request_table', compact('requests'));
}

 // Destroy Request
 public function destroyRequest($id)
 {
     $request = RequestModel::findOrFail($id);  // Use the aliased RequestModel
     $request->delete();

     return response()->json([
         'success' => true,
         'message' => 'Request deleted successfully'
     ]);
 }

 // Edit Request Form
 public function editRequest($id)
 {
     $request = RequestModel::findOrFail($id);  // Use the aliased RequestModel
     return view('superadmin.request_edit', compact('request'));
 }

 // Update Request
 public function updateRequest(HttpRequest $httpRequest, $id)  // Renamed the parameter to $httpRequest
{
    $httpRequest->validate([
        'request_name' => 'required|string|max:255',  // Adjust validation rules as per your data
        'request_description' => 'required|string|max:500',  // Example field
    ]);

    $requestToUpdate = RequestModel::findOrFail($id);  // Use the aliased RequestModel
    $requestToUpdate->update($httpRequest->all());  // Use the renamed variable

    return response()->json([
        'success' => true,
        'message' => 'Request updated successfully'
    ]);
}



// ✅ Final Request Table (Paginated method)
public function finalRequestTable()
{
    $finalRequests = FinalRequestModel::paginate(10); // 10 items per page
    return view('superadmin.finalrequest_table', compact('finalRequests'));
}

// Destroy Final Request
public function destroyFinalRequest($id)
{
    $finalRequest = FinalRequestModel::findOrFail($id);  // Use the aliased FinalRequestModel
    $finalRequest->delete();

    return response()->json([
        'success' => true,
        'message' => 'Final request deleted successfully'
    ]);
}

// Edit Final Request Form
public function editFinalRequest($id)
{
    $finalRequest = FinalRequestModel::findOrFail($id);  // Use the aliased FinalRequestModel
    return view('superadmin.finalrequest_edit', compact('finalRequest'));
}

// Update Final Request
public function updateFinalRequest(HttpRequest $httpRequest, $id)  // Renamed the parameter to $httpRequest
{
    $httpRequest->validate([
        'final_request_name' => 'required|string|max:255',  // Adjust validation rules as per your data
        'final_request_description' => 'required|string|max:500',  // Example field
    ]);

    $finalRequestToUpdate = FinalRequestModel::findOrFail($id);  // Use the aliased FinalRequestModel
    $finalRequestToUpdate->update($httpRequest->all());  // Use the renamed variable

    return response()->json([
        'success' => true,
        'message' => 'Final request updated successfully'
    ]);
}

// ✅ Request History Table (Alternate method for display)
public function requestHistoryTable()
{
    $requestHistories = RequestHistory::all();  // Use the aliased RequestHistoryModel
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
